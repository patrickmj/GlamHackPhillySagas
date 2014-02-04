<?php


class Saga_ImportController extends Omeka_Controller_AbstractActionController
{

    public function importAction()
    {
        set_time_limit(0);
        require_once(BASE_DIR . "/plugins/Saga/models/Importer/AbstractImporter.php");
        require_once(BASE_DIR . "/plugins/Saga/models/Importer/Manuscript.php");

        $importer = new Saga_Importer_Manuscript;
        $path = BASE_DIR . "/plugins/Saga/data/msDescs/Advocates_UK/MS-2129.xml";
        $path = BASE_DIR . "/plugins/Saga/data/msDescs/AM_Denmark/AM02-0001a-en.xml";


         //   $importer->loadTei($path);
         //   $importer->import($path);


        $folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Advocates_UK/*.xml");


        $folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Akureyri_Iceland/*.xml");
        $folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/AM_Denmark/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/AM_Iceland/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/BibStGen_France/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/BL_UK/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Bodleian_UK/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/BorgDist_Iceland/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/DKNVSB_Norway/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Harvard_USA/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/JohnsHop_USA/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/KB_Denmark/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/KB_Sweden/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Lbs_Iceland/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/NatlMusuem_Iceland/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/PrivColl_Iceland/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/RiksAmb_Sweden/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Riksark_Sweden/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/SkagDist_Iceland/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Skogar/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/STABI_Berlin_Germany/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/TrinColl_Ireland/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/UB_Bergen_Norway/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/UB_Lund_Sweden/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/UB_Oslo_Norway/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/UB_Rostock_Germany/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/UB_Uppsala_Sweden/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Winnipeg_Canada/*.xml");
        //$folderTei = glob(BASE_DIR . "/plugins/Saga/data/msDescs/Yale_USA/*.xml");

        $imported = array();
        foreach($folderTei as $path) {
            debug($path);
            $importer->loadTei($path);
            $importer->import($path);

        }

    }

    public function personAction()
    {
        set_time_limit(0);
        require_once(BASE_DIR . "/plugins/Saga/models/Importer/AbstractImporter.php");
        require_once(BASE_DIR . "/plugins/Saga/models/Importer/Person.php");

        $importer = new Saga_Importer_Person;
        $path = BASE_DIR . "/plugins/Saga/data/Authority_files/names.xml";
        $importer->loadTei($path);
        $importer->import($path);

    }
}