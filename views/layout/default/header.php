<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

    <title> UGEL 06  </title>
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <script src="<?php echo BASE_URL ?>public/js/libs/jquery-2.1.1.min.js"></script> 
    <script src="<?php echo BASE_URL ?>public/js/libs/jquery-ui-1.10.3.min.js"></script>  
    
    <!-- <script src="<?php echo BASE_URL ?>public/js/libs/jquery.min.js"></script> -->
    

    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/font-awesome.min.css">

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/smartadmin-skins.min.css">

    <!-- SmartAdmin RTL Support  -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/smartadmin-rtl.min.css">

    <input style="display: none;" type="text" name="lectura" id="lectura" value="<?php echo $_SESSION['lectura']; ?>">
    <input style="display: none;" type="text" name="escritura" id="escritura" value="<?php echo $_SESSION['escritura']; ?>">

    

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
             <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

             <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
             <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/demo.min.css">

             <!-- FAVICONS -->
             <link rel="shortcut icon" href="<?php echo BASE_URL ?>public/img/icono.ico" type="image/x-icon">
             <link rel="icon" href="<?php echo BASE_URL ?>public/img/icono.ico" type="image/x-icon">

             <!-- GOOGLE FONT -->
             <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

        <!-- Specifying a Webpage Icon for Web Clip 
        Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="<?php echo BASE_URL ?>public/img/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo BASE_URL ?>public/img/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo BASE_URL ?>public/img/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo BASE_URL ?>public/img/splash/touch-icon-ipad-retina.png">
        
        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- ValidaCampos -->
        <script src="<?php echo BASE_URL?>public/js/plugin/validasololetras/validCampoFranz.js"></script>

        <!-- ALERT DIALOG -->
        <link rel="stylesheet" href="<?php echo BASE_URL ?>public/js/plugin/alert_dialog/jquery-confirm.css"></script>  
        <link rel="stylesheet" src="<?php echo BASE_URL ?>public/js/plugin/alert_dialog/jquery-confirm.min.css"></script> 
        <script src="<?php echo BASE_URL ?>public/js/plugin/alert_dialog/jquery-confirm.js"></script>  
        <script src="<?php echo BASE_URL ?>public/js/plugin/alert_dialog/jquery-confirm.min.js"></script>  

        <!-- PAGE RELATED PLUGIN(S) -->
        <script src="<?php echo BASE_URL ?>public/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo BASE_URL ?>public/js/plugin/datatables/dataTables.colVis.min.js"></script>
        <script src="<?php echo BASE_URL ?>public/js/plugin/datatables/dataTables.tableTools.min.js"></script>
        <script src="<?php echo BASE_URL ?>public/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo BASE_URL ?>public/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <!-- -->
        <script src="<?php echo BASE_URL?>public/js/datatables/buttons.print.min.js"></script>
        <script src="<?php echo BASE_URL?>public/js/datatables/pdfmake.min.js"></script>        
        <script src="<?php echo BASE_URL?>public/js/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo BASE_URL?>public/js/datatables/buttons.bootstrap.min.js"></script>
        <script src="<?php echo BASE_URL?>public/js/datatables/jszip.min.js"></script>      
        <script src="<?php echo BASE_URL?>public/js/datatables/vfs_fonts.js"></script>
        <script src="<?php echo BASE_URL?>public/js/datatables/buttons.html5.min.js"></script>        
        <script src="<?php echo BASE_URL?>public/js/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="<?php echo BASE_URL?>public/js/datatables/dataTables.keyTable.min.js"></script>        
        <script src="<?php echo BASE_URL?>public/js/datatables/responsive.bootstrap.min.js"></script>
        <script src="<?php echo BASE_URL?>public/js/datatables/dataTables.scroller.min.js"></script>

        <!-- Libreria HIGHCHARTS (graficos) -->
        <script src="<?php echo BASE_URL?>public/js/plugin/highchartTable/highstock.js"></script>
        <script src="<?php echo BASE_URL?>public/js/plugin/highchartTable/highcharts.js"></script>
        <script src="<?php echo BASE_URL?>public/js/plugin/highchartTable/highcharts-3d.js"></script>
        <script src="<?php echo BASE_URL?>public/js/plugin/highchartTable/highcharts-more.js"></script>
        <script src="<?php echo BASE_URL?>public/js/plugin/highchartTable/solid-gauge-src.js"></script>

        <script
        src="<?php echo BASE_URL?>public/js/plugin/highchartTable/exporting.js"></script>
        <script
        src="<?php echo BASE_URL?>public/js/plugin/highchartTable/FileSaver.js"></script>

        
        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="<?php echo BASE_URL ?>public/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="<?php echo BASE_URL ?>public/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="<?php echo BASE_URL ?>public/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

        <?php if(isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>
            <?php foreach($_layoutParams['js'] as $layout): ?>
                <script src="<?php echo  $layout ?>" type="text/javascript"></script>
            <?php endforeach; ?>
        <?php endif; ?>

        <script type="text/javascript">
            $(document).on('ready',function(){
                $('.select2').select2({
                    placeholder: "Seleccione",
                    allowClear: true
                });

                $(".select2_multiple").select2({
                    allowClear: true
                });

                $('.datepicker').datepicker({
            //dateFormat : 'dd/mm/yy',        
            dateFormat : 'yy-mm-dd', 
        });
            });
        </script>

        <script type="text/javascript">
            $(function(){
        //Para escribir solo letras
        $('.letras').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');
        //Para escribir solo numeros    
        $('.numeros').validCampoFranz('0123456789');    
    });
</script> 

<style type="text/css">
/*<![CDATA[*/
 
.sobre{
background-color: lime;
width: 200px;
height: 300px;
}
.negro{
background-color: #000;
color: #FFF;
width: 200px;
height: 200px;
border: none;
}
/*]]>*/
</style>

<script type="text/javascript">
    $(function(){

        var lectura = $('#lectura').val();
        var escritura = $('#escritura').val();

        if(lectura == 1){
            $('button').attr('disabled',true);
        }else if (lectura == 0 && escritura==0){
            $('button').attr('disabled',true);
        }else{
            $('button').removeAttr("disabled")
        }




        //Para escribir solo letras
        //$('.letras').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou');
        //Para escribir solo numeros    
        //$('.numeros').validCampoFranz('0123456789');    
    });
</script> 



</head>

    <!--

    TABLE OF CONTENTS.
    
    Use search to find needed section.
    
    ===================================================================
    
    |  01. #CSS Links                |  all CSS links and file paths  |
    |  02. #FAVICONS                 |  Favicon links and file paths  |
    |  03. #GOOGLE FONT              |  Google font link              |
    |  04. #APP SCREEN / ICONS       |  app icons, screen backdrops   |
    |  05. #BODY                     |  body tag                      |
    |  06. #HEADER                   |  header tag                    |
    |  07. #PROJECTS                 |  project lists                 |
    |  08. #TOGGLE LAYOUT BUTTONS    |  layout buttons and actions    |
    |  09. #MOBILE                   |  mobile view dropdown          |
    |  10. #SEARCH                   |  search field                  |
    |  11. #NAVIGATION               |  left panel & navigation       |
    |  12. #RIGHT PANEL              |  right panel userlist          |
    |  13. #MAIN PANEL               |  main panel                    |
    |  14. #MAIN CONTENT             |  content holder                |
    |  15. #PAGE FOOTER              |  page footer                   |
    |  16. #SHORTCUT AREA            |  dropdown shortcuts area       |
    |  17. #PLUGINS                  |  all scripts and plugins       |
    
    ===================================================================
    
-->

<!-- #BODY -->
    <!-- Possible Classes

        * 'smart-style-{SKIN#}'
        * 'smart-rtl'         - Switch theme mode to RTL
        * 'menu-on-top'       - Switch to top navigation (no DOM change required)
        * 'no-menu'           - Hides the menu completely
        * 'hidden-menu'       - Hides the main menu but still accessable by hovering over left edge
        * 'fixed-header'      - Fixes the header
        * 'fixed-navigation'  - Fixes the main menu
        * 'fixed-ribbon'      - Fixes breadcrumb
        * 'fixed-page-footer' - Fixes footer
        * 'container'         - boxed layout mode (non-responsive: will not work with fixed-navigation & fixed-ribbon)
    -->
    <body class="smart-style-1">

        <!-- HEADER -->
        <header id="header">
            <div id="logo-group">

                <!-- PLACE YOUR LOGO HERE -->
                <span id="logo"> <img src="<?php echo BASE_URL ?>public/img/logo_ugel06.jpg" alt="UGEL06"></span>
                <!-- END LOGO PLACEHOLDER -->

                <!-- Note: The activity badge color changes when clicked and resets the number to 0
                Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
                
                
            </div>            

            <!-- pulled right: nav area -->
            <div class="pull-right">

                <!-- collapse menu button -->
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Ocultar Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <!-- end collapse menu -->                    

                <!-- logout button -->
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="<?php echo BASE_URL?>index/logout" title="Cerrar Sesion"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <!-- end logout button -->       

                <!-- fullscreen button -->
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Expandir"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
                <!-- end fullscreen button -->         

            </div>
            <!-- end pulled right: nav area -->

        </header>
        <!-- END HEADER -->

        <!-- Left panel : Navigation area -->
        <!-- Note: This width of the aside area can be adjusted through LESS variables -->
        <aside id="left-panel">

            <!-- User info -->
            <div class="login-info">
                <span> <!-- User image size is adjusted inside CSS, it should stay as it --> 

                    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                        <img src="<?php echo BASE_URL ?>public/img/profiles/<?php echo $_SESSION['foto']; ?>" alt="me"  class="online" /> 
                        <span> <?php echo $_SESSION['user']; ?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a> 
                    
                </span>
            </div>
            <!-- end user info -->

            <!-- NAVIGATION : This navigation is also responsive-->
            <nav>
                <!-- 
                NOTE: Notice the gaps after each icon usage <i></i>..
                Please note that these links work a bit different than
                traditional href="" links. See documentation for details.
            -->

            <ul>
                <!--PANEL -->
                <?php if (isset($_SESSION['menu']['MENU_PAN'])):?>
                    <li class="">
                        <a href="#" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">PANEL</span></a>
                        <ul>
                            <?php foreach ($_SESSION['menu']['MENU_PAN'] as $submenu):?>
                                <li class="" ><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><?php echo $submenu['DESCRIPCION']?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                <?php endif;?>  
                <!--END PANEL -->

                <?php if (isset($_SESSION['menu']['MENU_ACT'])):?>
                    <li class="top-menu-invisible">
                        <a href="#" title="Dashboard"><i class="fa fa-lg fa-fw fa-archive"></i> <span class="menu-item-parent">ACTIVO</span></a>
                        <ul>
                            <?php foreach ($_SESSION['menu']['MENU_ACT'] as $submenu):?>
                                <li class="" ><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><?php echo $submenu['DESCRIPCION']?></a></li>
                            <?php endforeach;?>
                        </ul>   
                    </li>
                <?php endif;?> 

                <?php if (isset($_SESSION['menu']['MENU_INV'])):?>
                    <li class="top-menu-invisible">
                        <a href="#" title="Dashboard"><i class="fa fa-lg fa-fw fa-sliders"></i> <span class="menu-item-parent">INVENTARIO</span></a>
                        <ul>
                            <?php foreach ($_SESSION['menu']['MENU_INV'] as $submenu):?>
                                <li class="" ><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><?php echo $submenu['DESCRIPCION']?></a></li>
                            <?php endforeach;?>
                        </ul>   
                    </li>
                <?php endif;?> 

                <?php if (isset($_SESSION['menu']['MENU_PER'])):?>
                    <li class="">
                        <a href="#"><i class="fa fa-lg fa-fw fa-legal"></i> <span class="menu-item-parent">MANTENIMIENTO</span></a>
                        <ul>
                            <?php foreach ($_SESSION['menu']['MENU_PER'] as $submenu):?>
                                <li class="" ><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><?php echo $submenu['DESCRIPCION']?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                <?php endif;?> 

                <?php if (isset($_SESSION['menu']['MENU_REP'])):?>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">REPORTES</span></a>
                        <ul>
                            <?php foreach ($_SESSION['menu']['MENU_REP'] as $submenu):?>
                                <li class="" ><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><?php echo $submenu['DESCRIPCION']?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php if (isset($_SESSION['menu']['MENU_ACTA'])):?>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-file-text"></i> <span class="menu-item-parent">ACTAS</span></a>
                        <ul>
                            <?php foreach ($_SESSION['menu']['MENU_ACTA'] as $submenu):?>
                                <li class="" ><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><?php echo $submenu['DESCRIPCION']?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                <?php endif;?> 

                <?php if (isset($_SESSION['menu']['MENU_SEG'])):?>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-lock"></i> <span class="menu-item-parent">SEGURIDAD</span></a>
                        <ul>
                            <?php foreach ($_SESSION['menu']['MENU_SEG'] as $submenu):?>
                                <li class="" ><a href="<?php echo BASE_URL . $submenu['UBICACION']?>"><?php echo $submenu['DESCRIPCION']?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                <?php endif;?>              

            </ul>
        </nav>


        <span class="minifyme" data-action="minifyMenu"> 
            <i class="fa fa-arrow-circle-left hit"></i> 
        </span>

    </aside>
        <!-- END NAVIGATION -->