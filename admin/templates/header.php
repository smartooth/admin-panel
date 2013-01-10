<?php $nav = array(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>LD Admin Panel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <link href="/css/bootstrap.css" rel="stylesheet">
        <link href="/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="/css/font-awesome.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
            }
            @media (max-width: 979px) {
                body {
                    padding-top: 0;
                }
            }
            .sidebar-nav {
                padding: 9px 0;
            }
            /* Navbar tweaks */
            body > .navbar {
                font-size: 13px;
            }
            /* Change the docs' brand */
            body > .navbar .brand {
                padding-right: 0;
                padding-left: 0;
                margin-left: 20px;
                float: left;
                font-weight: bold;
                color: #000;
                text-shadow: 0 1px 0 rgba(255,255,255,.1), 0 0 30px rgba(255,255,255,.125);
                -webkit-transition: all .2s linear;
                -moz-transition: all .2s linear;
                transition: all .2s linear;
            }
            body > .navbar .brand:hover {
                text-decoration: none;
                text-shadow: 0 1px 0 rgba(255,255,255,.1), 0 0 30px rgba(255,255,255,.4);
            }
            /* end */
            .changelog-header{
                color:#fff;
                height:24px;
                background-color:#3e408c;
            }
            .major{
                background-color:#fbe6ef;
                font-weight:bold;
            }
            .author{
                color: #8D4848;
                display: inline;
            }
        </style>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="/js/jquery-1.8.3.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
    </head>