<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>css/style.css" >      
    </head>
    <body>
        <div id="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!--<a class="navbar-brand" href="index.html">SB Admin</a>-->
                    <a class="navbar-header" href="<?php echo base_url(); ?>">
                        <img width="225" height="50" border="0" title="<?php echo lang('TITLE'); ?>" alt="LOGO" src="<?php echo base_url(); ?>images/logo.png" />
                    </a>
                </div>
                <!-- Top Menu Items -->
                <?php if (isset($_SESSION['user'])): ?>
                    <ul class="nav navbar-right top-nav">

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> 
                                <?php echo $_SESSION['user']['prenom'] . ' (' . $_SESSION['user']['profil'] . ')'; ?> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url("utilisateur/my_account/" . $_SESSION['user']['id_utilisateur']); ?>"><i class="fa fa-fw fa-user"></i>  <?php echo lang('PROFILE'); ?></a>
                                </li>
                                <!--                                    <li>
                                                                        <a href="#"><i class="fa fa-fw fa-gear"></i>  <?php echo lang('SETTINGS'); ?></a>
                                                                    </li>-->
                                <li class="divider"></li>

                                <li>
                                    <a href="<?php echo site_url("connexion/logout"); ?>"><span class="fa fa-fw fa-sign-out">
                                        </span> <?php echo lang('LOGOUT'); ?> 
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
                    <!--<div id="sidebar-wrapper">-->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">

                        <ul class="nav navbar-nav side-nav">
                            <?php $user_profil = strtolower($_SESSION['user']['profil']); ?>
                            
                             <?php if ((strpos($user_profil, 'compta') !== 0) && (strpos($user_profil, 'transit') !== 0)) : ?>
                                <li class="<?php echo $active == 'receipt' ? 'active' : '' ?>">
                                    <a href="<?php echo site_url("receipt"); ?>"><i class="fa fa-fw fa-download"></i>&nbsp;<?php echo lang('RECEIPTS'); ?></a>
                                </li>
                             <?php endif; ?>
                            <?php if ($user_profil === 'manager') : ?>
                                <li class="<?php echo $active == 'profile' ? 'active' : '' ?>">
                                    <a href="<?php echo site_url("profile"); ?>"><i class="fa fa-fw fa-user"></i>&nbsp;<?php echo lang('PROFILES'); ?></a>
                                </li>
                                <li class="<?php echo $active == 'user' ? 'active' : '' ?>">
                                    <a href="<?php echo site_url("user"); ?>"><i class="fa fa-fw fa-users"></i>&nbsp;<?php echo lang('USERS'); ?></a>
                                </li>
                                <li class="<?php echo $active == 'parameters' ? 'active' : '' ?>">
                                    <a href="<?php echo site_url("parameters"); ?>"><i class="fa fa-fw fa-gear"></i>&nbsp;<?php echo lang('PARAMETERS'); ?></a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>
                <?php endif; ?>
                <!--</div>-->
                <!-- /.navbar-collapse -->
            </nav>


            <div id="page-wrapper">
                <div class="container-fluid">
                    <?php if (isset($_SESSION['user'])) : ?>

                        <div class="row">

                            <div class="col-lg-12">
                                <div class="content clearfix">

                                    <!-- hide add button if configuration page --> 
                                    <?php if (!isset($configuration)) : ?>
                                    
                                        <?php
                                        $authorized_operations = array();

                                        if (!empty($_SESSION['user']['authorized_operations']))
                                        {
                                            $authorized_operations = json_decode($_SESSION['user']['authorized_operations'], true);
                                        }

                                        ?>
                                        <?php if (isset($authorized_operations['delete']) && $authorized_operations['delete']) : ?>
                                            <div class="pull-right">
                                                <a href="#" onclick="loadForm('<?php echo $form_link; ?>')" data-toggle="modal" data-target="#form-content" class="btn btn-primary btn-large"><?php echo lang('ADD'); ?></a>
                                            </div>
                                            <div class="clearfix">
                                            </div>
                                        <?php endif ?>
                                    <?php endif; ?>

                                    <div class="msg <?php echo isset($error) && !$error ? 'success' : 'alert-danger fade in'; ?>">
                                        <center><?php echo isset($msg) ? urldecode($msg) : ''; ?></center>
                                    </div>
                                <?php endif; ?>
