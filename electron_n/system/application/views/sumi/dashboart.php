<body class="page-body  page-fade">

<div class="page-container">
    <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
    <?php $this->load->view("sumi/incluir/menu"); ?>

    <div class="main-content">

        <?php $this->load->view("sumi/incluir/barra");?>

        <hr/>

        <?php $this->load->view('sumi/'.$vista);?>

        <br/>
        <!-- Footer -->
        <footer class="main" style="text-transform: capitalize;">
            © 2017 Cooperativaelectron | Developed By <a href="http://insumoslacandelaria.com.ve" target="_blank"><strong>Jud.prog && mjbr</strong></a>
        </footer>
    </div>
</div>
