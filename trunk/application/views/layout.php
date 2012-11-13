<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="utf-8" />
        <meta name="keywords" content="排課, 國小, 課表" />
        <meta name="description" content="國小排課系統" />
        <?= HTML::style('css/YUI CSS Rest.css') ?>
        <?= HTML::style('css/global.css') ?> 
        <title>OST排課系統</title>
    </head>
    <body>
        <div id="wrapper">
            <header>
                <div id="banner">
                    OST排課系統
                </div>
                <div id="login">
                    <a href="login">登入</a>
                </div>
            </header>
            <nav>
                <ul id="user_nav">
                    <li>
                        <a href="">班級課表</a>
                    </li>
                    <li>
                        <a href="">教師課表</a>
                    </li>
                    <li>
                        <a href="">教室課表</a>
                    </li>
                </ul>
                <ul id="teacher_nav">
                    <li>
                        <a href="">個人排課需求設定</a>
                    </li>
                </ul>
                <ul id="admin_nav">
                    <li>
                        <a href="">教師排課限制設定</a>
                    </li>
                    <li>
                        <a href="">課程排課需求設定</a>
                    </li>
                    <li>
                        <a href="">課程排課限制設定</a>
                    </li>
                    <li>
                        <a href="">帳號密碼管理</a>
                    </li>
                </ul>
            </nav>
            <section></section>
        </div>
        <footer>
            <a href="http://code.google.com/p/open-school-timetable/">程式專案網站</a>
        </footer>
        <?= HTML::script('javascript/jQuery.js') ?>
    </body>
</html>