<!DOCTYPE html>
<html lang="cs">

<head>
    <title>Galerie</title>
    <link href="css/style.css" rel="stylesheet" />
    <link rel="icon" href="img/logo.png">
    <meta name="description" content="Galerie jako webová služba">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Jan Černý" />
    <script src="https://kit.fontawesome.com/afcc9b0842.js" crossorigin="anonymous"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="javascripts/delete.js"></script>
    <script type="text/javascript" src="javascripts/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
    <script type="text/javascript" src="javascripts/download.js"></script>
</head>

<body>
    <?php
    include_once("php/form/states.php");
    include_once("php/footer.php");
    include_once("php/admin/switch_admin.php");
    /* Jeden z nejdůležitějších scriptů.
         * Zařizuje zobrazení hlavní strany.
         * Zařizuje odkazy a organizuje většinu dat posílající se ostataním php a javascript scriptům.
         */
    class index extends states
    {
        /* Konstruktor, který zjištuje stav administrace, zdali se uživatel nachází v nějaký složce, nebo v přehledu všech složek 
            * podle toho, kde se člověk nachází pak volá danou funkci
            */
        public function __construct()
        {
            $admin = new switch_admin("");
            $admin_state = $admin->get_admin();
            $admin_foldername = $admin->get_foldername();
            if (isset($_REQUEST["file_name"])) {
                $admin->set_foldername(htmlspecialchars($_REQUEST["file_name"]));
                $this->load_image(htmlspecialchars($_REQUEST["file_name"]), $admin_state);
            } else if ($admin_foldername != NULL and  $admin_foldername != "Veřejný obsah") {
                $this->load_image($admin_foldername, $admin_state);
            } else {
                $this->is_logged($admin_state);
                $this->load($admin_state);
            }
        }
        /* Funkce, která zavolá funkce index v php souboru states.php
            * zjištuje, zdali se člověk přihlásil v pořádku
            * pokud vše v pořádku proběhlo, states vrátí hlavičku a nav stránky
            */
        public function is_logged(string $admin_state)
        {
            echo ($this->index("files", $admin_state));
        }
        /* Funkce, která je volaná konstruktorem
            * vypíše všechny složky uživatele a patičku stránky
            */
        public function load()
        {
            $email = $this->get_email();
            $load = include_once("php/load/load_files.php");
            $admin = new switch_admin("");
            $admin_state = $admin->get_admin();
            $load = new load_files($email, $admin_state);
            echo ($load->get_sql_data($email, $admin_state));
            new footer("home", $this->get_email());
        }
        /* Funkce, která je volaná konstruktorem
            * vypíše všechny obrázky ve složce uživatele a patičku stránky
            */
        public function load_image(string $REQUEST, $admin_state)
        {
            echo ($this->index("imgs", $admin_state));
            include_once("php/load/get_file.php");
            $admin = new switch_admin("");
            $admin_state = $admin->get_admin();
            $html_images = new get_file($REQUEST, $admin_state);
            echo ($html_images->get($REQUEST, $admin_state));
            new footer("files", $this->get_email());
        }
    }
    $obj = new index();
    ?>
    <script type="text/javascript" src="javascripts/imgs.js"></script>
    </div>
    </main>
</body>

</html>
