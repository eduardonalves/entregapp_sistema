<?php
App::uses('AppController', 'Controller');
class RestImagesController extends AppController {
    public $uses = array('Produto');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');

    
    public function uploadimage() {
        header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
        date_default_timezone_set("Brazil/East");


        $ultimomensagen='ok';
        $uploadfile = $_POST['fileName'];
        $auxName= explode('.',$uploadfile);
        $ext='.jpg';
        if(isset($auxName[1])){
          $extension = strtolower($auxName[1]);
          switch ($extension) {
            case 'gif':
              $ext='.gif';
              break;
              case 'jpeg':
                $ext='.jpeg';
                break;
                case 'png':
                  $ext='.png';
                  break;
            default:
              $ext='.jpg';
              break;
          }
        }
        $uploadfilename = $_FILES['file']['tmp_name'];

        $tipo = $_FILES['file']['type'];

        if($tipo == 'image/jpeg' || $tipo == 'image/gif' || $tipo == 'image/png'  || $tipo == 'image/jpg' || $tipo == 'image/jpeg')
        {
          $ultimomensagen="ok";
        }else{
          $ultimomensagen="erro formato";
        }
        if($ultimomensagen !="erro formato"){
          $dest = ROOT . DS . 'app' . DS . 'webroot' . DS;
          $dest .= 'img'. DS .date('Y'). DS .date('m'). DS . date('d'). DS ;
          $url = "http://".$_SERVER['HTTP_HOST']."/img";
          $url .= DS .date('Y'). DS .date('m'). DS . date('d'). DS ;
          if (!file_exists($dest)) {
              mkdir($dest, 0777, true);
          }

         $nomedoArquivo = rand(1000,999999);
          $nomedoArquivo .= $nomedoArquivo."_";
         if(!empty($_POST['id'])){
           $nomedoArquivo.=$_POST['id'];
         }

         $nomedoArquivo = str_ireplace(' ','', $nomedoArquivo);
         $nomedoArquivo.=$ext;
         $url .= $nomedoArquivo;
          if(move_uploaded_file($_FILES["file"]["tmp_name"], $dest.$nomedoArquivo)){
              $this->compress($dest.$nomedoArquivo, $dest.$nomedoArquivo,50);
              $ultimomensagen= $url;
          }else{
              $ultimomensagen="erro upload";
          } // Move from source to destination (you need write permissions in that dir)
          //$requestData['Produto'][$value] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$requestData['Produto']['filial_id'].'/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
        }

        $this->set(array(
              'ultimomensagen' => $ultimomensagen,
              '_serialize' => array('ultimomensagen')
            ));
    }
    function compress($source, $destination, $quality)
    {
      $info = getimagesize($source);
      if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
      elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
       elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
       imagejpeg($image, $destination, $quality);
        return $destination;
    }
}
