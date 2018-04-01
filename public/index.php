<?php
namespace monochrome;

require_once '../src/globalConstants.php';
require_once '../src/functions.php';
require_once '../class/Autoload.php';
Autoload::on();

use DBconnection;

/**
* @var string|NULL $errorMsg store eventual generic error message to be displayed instead of the normal page
*/
$errorMsg = null;

/**
* @var array $errorDivs store eventual error message to be displayed before the different <input> elements
*/
$errorDivs = [];

/**
* @var bool $showingAdmin wether the admin section must be displayed or not
*/
$showingAdmin = false;

#this page must be used with the GET method
if (0 != count($_GET)) {
    $errorMsg = 'Cette page est prévue pour être utilisée avec la méthode POST et non GET.
        Abandon.';
} else {
    $conn = new PDOconnection();

    /**
    * store the values of the <input> elements of the form after validation
    * @var array $entry
    */
    $entry = [];


    # the method was POST – it's time to do some validation
    if (0 != count($_POST)) {
        $neededFields = [ 'firstName', 'lastName', 'civility' ];
        $allowedValuesForGender = [ 'F', 'H', 'I' ];

        #error flag – if unset at the end of  validation, a request can be made
        $errFlag = false;


        #validation – presence of the needed fields
        foreach ($neededFields as $field) {
            if (empty($_POST[$field])) {
                $errFlag = true;
                $errorDivs[$field] =
                    '<div class="error">Cette entrée est obligatoire</div>';
            } else {                 #protection against malevolent admin
                $entry[$field] =
                    htmlspecialchars(addslashes($_POST[$field]));
            }
        }


        #validation of the value of the <select> element
        $gotValue = $_POST['civility'];
        $res = [];
        #
        if (! in_array($gotValue, $allowedValuesForGender)) {
            $errFlag = true;
            $errorDivs['civility'] =
                '<div class="error">Veuillez sélectionner un genre</div>';
            $res['unchoosen'] = 'selected';
        } else {
            #each <option> element inside the <select> can
            # have the "selected" attribute ...
            foreach ($allowedValuesForGender as $val) {
                $res[$val] = ( $val == $gotValue ) ?
                                'selected'
                                : '';
            }
        }
        $entry['civility'] = $res;

     
        # validation is done – now we try the request
        if ($errFlag) {
            $showingAdmin = true;
        } else {
            $showingAdmin = false;
            $entry = [];
            echo "La requête est ok !";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>checkpoint n°1</title>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--to deactivate the old "compatibility with that old IE" mode of edge -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Latest compiled and minified boostrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous">

    <link rel="stylesheet" href="default.css">

  </head>

  <body>
 
 <!-- TODO: ADD A BANNER IN CASE OF NOJS AND A NOJS.CSS -->

    <header>
        <h1>Content of the SQL database</h1>
        <h2>obtained with PDO</h2>
    </header>

    <main>

<?php

if (null != $errorMsg) {
    # only show an error message in the page
    echo formatError($errorMsg);
    echo "<a class=\"btn btn-primary\" title=\"Repartir d'une page vierge\"
             href=\"?\">\nRéessayer</a>";
} elseif (! $conn->getSuccess()) {
    // this part is shown in case of connection error
    echo formatError(DEBUGING ? $conn->getLastMsg() : null);
} else {
// TODO : add error handling
    $conn->doQuery();
?>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Civilité</th>
                    <th>NOM et prénom</th>
                </tr>
            </thead>

            <tbody>
<?php
while ($data = $conn->fetchAsAssoc()) {
    echo formatTableRow(
        $data['civility'],
        fullName($data['firstName'], $data['lastName'])
    );
}

?>
<!-- #TODO : ajouter <tfoot> si nbentrées > valeur -->

           </tbody>
        </table>
      </div> <!-- .table-responsive -->


      <hr>

    
    <!-- this div is used to have both its children occupy same space -->
      <div class="myContainer">
        <label for="unhider1" class="btn btn-primary pull-left"
            title="afficher la section permettant de modifier les entrées" role="button">
            Afficher la section administrative &gt;&gt;&gt;
        </label>
     
        <!-- the only role of this input is to toggle the visibility of the
         following section -->
        <input type="checkbox" role="aria-hidden"
                <?= $showingAdmin ? 'checked' : '' ?>
                id="unhider1" class="unhider">
        
        <!-- this section is shown/hidden without JS –
         but with a combination of CSS, and a checkbox -->
        <section class="toggableHiding">
            <!-- a SECOND label for the same input -->
            <label for="unhider1" class="btn btn-primary pull-left"
                    title="cacher cette section"
                    role="button">
                &lt;&lt;&lt; Cacher la section administrative
            </label>
            <br>

            <h2>Addition d'entrées</h2>


            <form method="POST" action="" class="form-inline">
 
                <fieldset class="form-group">

                    <label for="civility">
                        Genre
                        <?= $errorDivs['civility'] ?? '' ?>
                        <select name="civility" id="civility" required>
                            <option value=""
                                <?= $entry['civility']['unchoosen'] ?? '' ?> >
                                choisir ...
                            </option>
                            <option value="F"
                                <?= $entry['civility']['F'] ?? '' ?> >
                                Féminin
                            </option>
                            <option value="H"
                                <?= $entry['civility']['H'] ?? '' ?> >
                                Masculin
                            </option>
                            <option value="I"
                                <?= $entry['civility']['I'] ?? '' ?> >
                                Autre
                            </option>
                        </select>
                    </label>
                
                    <label for="firstName">
                        Prénom
                        <?= $errorDivs['firstName'] ?? '' ?>
                        <input name="firstName" id="firstName" required 
                            placeholder="Prénom ..." 
                            value="<?= $entry['firstName'] ?? '' ?>">
                    </label>

                    <label for="lastName">
                        Nom
                        <?= $errorDivs['lastName'] ?? '' ?>
                        <input name="lastName" id="lastName" required 
                            placeholder="Nom de famille ..." 
                            value="<?= $entry['lastName'] ?? '' ?>">
                    </label>

<!--TODO : add a "reset" button with JS, and grey the "send" button-->

<!--TODO: make this work -->
<!--                    <button class="needJS" value="removeEntry" disabled> -->
  <!--TODO: better label (bootstrap glyph)-->
<!--                       Annuler cette entrée
                    </button>
-->                </fieldset>


                <div class="form-group">
<!--TODO: singulier/pluriel via php -->
                    <input type="submit" name="stdSubmit"
                        title="Ajouter l'entrée à la liste"
                        value="Valider">
<!--TODO: make this work -->
<!--                    <button class="needJS" value="addEntry" disabled>
-->
<!--TODO: better label (bootstrap glyph)-->
<!--                        Ajouter une entrée
                    </button>
-->                </div>

            </form>

        </section>

      </div> <!-- .myContainer -->

<?php
}
?>
    </main>

  </body>
</html>