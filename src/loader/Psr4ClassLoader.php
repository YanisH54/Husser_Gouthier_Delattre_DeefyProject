<?php


class Psr4ClassLoader {

    private string $prefixe;
    private string $racine;

    public function __construct(string $prefixe, string $racine){

        $this->prefixe = $prefixe;
        $this->racine = rtrim($racine, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function loadClass(string $className){
        print("Nom namespace : " .$className . "\n");

        $nom = substr($className,strlen($this->prefixe));
        print("Enlever le prefixe : " . $nom . "\n");

        $nom = $this->racine . $nom . ".php";
        print("Ajout racine et .php : " . $nom . "\n");

        $nom = str_replace("\\","/",$nom);
        print("Remplacer les / : " . $nom . "\n");

        

        if (is_file($nom)){
            print("Fichier trouvé");
            require_once $nom;
        } else
            print("Fichier non trouvé");
    }

    public function register(){
        spl_autoload_register([$this,'loadClass']);
    }

}

