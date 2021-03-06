<?php
class Controller{
/*c'est la classe principale qui sera utilié par tout les sous-controller.
class controller contient tout les automatisme généraux du site.
*/
    var $vars = array();
    var $layout = 'default';

    function __construct(){
        if(isset($_POST)){
            $this->data = $_POST;
        }
        if(isset($this->models)){
            foreach($this->models as $v){
                $this->loadModel($v);
            }
        }
    }
//permet de faire passer le contenu de $d à la view.
  function set($d){
        $this->vars = array_merge($this->vars,$d);
    } 

//essaye d'inclure le fichier qui correspond à sa view.
    function render($filename){
        extract($this->vars);//extrait les données de $var
        ob_start(); 

        //appelle le fichier de la view/dossier/fichier.php
        require(ROOT.'views/'.get_class($this).'/'.$filename.'.php');

        //rapatrie le contenu du fichier de la view dans $content_for_layout(moyennant un 'ob_get_clean').
        $content_for_layout = ob_get_clean();

        //var_dump($filename);
        if($this->layout==false){//si pas de layout
            echo $content_for_layout;//affiche quand même le $content_for
        }else{
            require(ROOT.'views/layout/'.$this->layout.'.php');
            //sinon layout déja défini, alors chercher le fichier dans layout avec: $this->layout == $filename. 
        }
    }

    //permet de charger un model.
    function loadModel($name){
        require_once(ROOT.'models/'.strtolower($name).'.php');
        $this->$name = new $name(); 
    }



/////////////////////////////////////////////////////////////////////////
//////////////////////////   PHASE DE TEST   ////////////////////////////
/////////////////////////////////////////////////////////////////////////
public function pagination($d){
    $nbr = Model::count();
    //$nbr = $this->count();//vérifier si la fonction dans coremodel est bien appellée.

        $nbArt = $nbr['nbArt'];
        $perPage = 4;
        $nbPage = ceil($nbArt/$perPage);

        if(isset($_GET['p']) && $_GET['p']>0 && $_GET['p']<=$nbPage){
        $cPage = $_GET['p'];
        }else{
            $cPage = 1;
        }

$_SESSION['cPage'] = $cPage;
$_SESSION['nbPage'] = $nbPage;
$d ='';
//$d = $this->$j;//getSpecial($cPage,$perpage);

  /*for($i=1;$i<=$nbPage;$i++){
     if($i==$cPage){
     echo "$i / ";
     }else{ 
    echo '<a href="?p='.$i.'">'.$i.'</a> /';

 } }*/   
}

//////////////////////////   FIN PHASE DE TEST   ////////////////////////
/////////////////////////////////////////////////////////////////////////

}

?>
