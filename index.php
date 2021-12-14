<?php
    require 'vendor/autoload.php';

    $uri_xml = "https://raw.githubusercontent.com/briannzw/UAS-Lab-Semantic-Web/master/xml/PWII2_Tugas%202_201401042.xml";
    $raw_file = file_get_contents($uri_xml);
    $xml = simplexml_load_string($raw_file);

    $sparql = new \EasyRdf\Sparql\Client('https://dbpedia.org/sparql');

    $query = "
    SELECT DISTINCT ?genre_uri ?genre
    WHERE{
        ?game rdf:type dbo:VideoGame.
        ?game dbo:genre ?genre_uri.
        ?genre_uri rdfs:label ?genre.
        FILTER(LANG(?genre) = 'en' || LANG(?genre) = '').
    }
    ORDER BY (?genre)
    ";

    $result = $sparql->query($query);
?>

<html>

<head>
    <title>Project UAS Lab Semantic Web - 201401042</title>
    <link rel="icon" href="img/logo.png">
    <!--Fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    </link>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css">

    <!--jquery & jquery redirect-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.redirect@1.1.4/jquery.redirect.min.js"></script>

</head>

<body>
    <h1 class="title mt-5">Discover Games</h1>

    <div class="main-container d-flex align-items-center mt-4 py-1 nav-bg">
        <div class="row card-title w-100 ps-4 my-auto">
            <span class="sub-title p-0">Search by Genre</span>
            <div class="collapse" id="genre-list">
            <div class="d-flex flex-wrap">
            <?php   foreach($result as $row) :
                        $detail = [
                            'genre'=> !empty(($row->genre)->getValue()) ? $row->genre : "",
                            'genre_uri'=> !($row->genre_uri)->isBNode() ? $row->genre_uri : "",
                        ];
                        
                        $detail["genre_uri"] = str_replace("http://dbpedia.org/resource/", "", $detail["genre_uri"]);
            ?>
                <div class="col-auto genre mb-1" uri="<?= $detail['genre_uri'] ?>"><?= $detail['genre'] ?></div>
            <?php   endforeach; ?>
            </div>
            </div>
            <div class="d-flex flex-wrap p-0">
                <a class="btn genre" data-bs-toggle="collapse" data-bs-target="#genre-list">Expand</a>
            </div>
        </div>
        <div class="ms-2 me-2">
            <form class="search_bar d-flex my-auto">
                <input class="search_input form-control me-2" type="text" placeholder="search" aria-label="">
                <a id="#search-button" class="search_icon"><i class="fas fa-search"></i></a>
            </form>

            <div class="dropdown">
                <div class="dropdown-toggle" data-bs-toggle="dropdown" id="dropdown-toggle" aria-expanded="false"></div>
                <ul class="dropdown-menu dropdown-menu-end" id="suggestion-box">
                </ul>
            </div>
        </div>
    </div>

    <div class="main-container">
        <p class="text-white mt-5">FEATURED & RECOMMENDED</p>
    </div>

    <?php
    $game_count = count($xml->children());
    $i = 0;
    foreach ($xml->children() as $game) :
        $i++;
    ?>
    <?php if($i == 1) : ?>
        <div class="card mb-5" style="display:flex; position:relative; left:10rem; margin-right:20rem;">
    <?php else : ?>
        <div class="card my-5" style="display:flex; position:relative; left:10rem; margin-right:20rem;">
    <?php endif; ?>
            <div class="row no-gutters">
                <div class="col">
                    <!--awal carousel-->
                    <div id="Carousel-<?= $i ?>" class="carousel slide carousel-fade" data-bs-ride="carousel" data-pause="hover">
                        <div class="carousel-indicators">
                        <?php $link_count = count($game->links->children());
                                for($j = 1; $j <= $link_count; $j++) :
                                    if($j == 1) :
                        ?>
                                        <button type="button" data-bs-target="#Carousel-<?= $i ?>" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <?php       else : ?>
                                        <button type="button" data-bs-target="#Carousel-<?= $i ?>" data-bs-slide-to="<?= ($j - 1) ?>" aria-label="Slide <?= $j ?>"></button>
                        <?php       endif; ?>
                        <?php   endfor; ?>
                        </div>
                        <div class="carousel-inner ratio ratio-16x9">
                            <?php $j = 0; 
                                foreach($game->links as $link) : 
                                    foreach($link->video as $video) : 
                                        $j++;
                                        if($j == 1) : 
                            ?>
                                            <div class="carousel-item active">
                                                <iframe src="<?= $video ?>" width="100%" height="100%"></iframe>
                                            </div>
                            <?php       else : ?>
                                            <div class="carousel-item">
                                                <iframe src="<?= $video ?>" width="100%" height="100%"></iframe>
                                            </div>
                            <?php       endif;      ?>
                            <?php   endforeach; ?>
                            <?php   $video_count = $j;
                                    $j = 0;
                                    foreach($link->image as $image) : 
                                        $j++;
                                        if(($j == 1) && ($video_count == 0)) : 
                            ?>
                                            <div class="carousel-item active">
                                                <img src="<?= $image ?>" class="d-block w-100" alt="..." />
                                            </div>
                            <?php       else : ?>
                                            <div class="carousel-item">
                                                <img src="<?= $image ?>" class="d-block w-100" alt="..." />
                                            </div>
                            <?php       endif; ?>
                            <?php   endforeach; ?>
                        <?php   endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#Carousel-<?= $i ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#Carousel-<?= $i ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <!--akhir carousel-->
                </div>
                <div class="col-md-4">
                    <div class="card-body p-0">
                        <img src="<?= $game->cover ?>" class="d-block w-100" alt="..." />
                        <h5 class="card-title mt-2">
                            <?= $game->name ?>
                        </h5>
                        <p class="card-text text-muted ">RELEASE DATE: <span class="txt-gray">
                                <?= $game->release_date ?>
                            </span></p>
                        <?php foreach($game->developer as $developer) : ?>
                            <p class="card-text text-muted m-0">DEVELOPER: &#160;&#160;&#160;&#160;&#160;<span class="txt-gray">
                                    <?= $developer ?>
                                </span></p>
                        <?php endforeach; ?>
                        <p class="card-text text-muted">PUBLISHER: &#160;&#160;&#160;&#160;&#160;&#160;<span class="txt-gray">
                                <?= $game->publisher ?>
                            </span></p>
                        <div class="card-text">
                            <div class="row mx-0">
                                <?php foreach($game->genre as $genre) : ?>
                                    <div class="col-auto genre mb-1" uri=<?= $genre["uri"] ?> wiki=<?php if($genre["wiki"]) echo $genre["wiki"] ?> subject=<?php if($genre["subject"]) echo $genre["subject"] ?>>
                                        <?= $genre ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <div class="container pb-5"></div>

    <!-- Searchbar Script -->
    <script>
        $(function(){
            $(document).ready(function() {
                $(".search_input").keyup(function(){
                        text = $('.search_input').val();
                        if((text != "") && (text.length >= 3)){
                            $.ajax({
                                url: 'action_search.php',
                                method: 'POST',
                                data: {
                                    query: text
                                },
                                success: function(data){
                                    if($('#dropdown-toggle').hasClass('show')){
                                        $('#dropdown-toggle').trigger('click.bs.dropdown');
                                    }
                                    $("#suggestion-box").html(data);
                                    $('#dropdown-toggle').trigger('click.bs.dropdown');
                                }
                            });
                        }
                        else {
                            $("#suggestion-box").html("");
                            $('.dropdown').removeClass('open');
                        }
                });
                $(".search_input").focus(function() {
                    if($("#suggestion-box").html() != ""){
                        if($('#dropdown-toggle').hasClass('show')){
                            $('#dropdown-toggle').trigger('click.bs.dropdown');
                        }
                        $('#dropdown-toggle').trigger('click.bs.dropdown');
                    }
                });
                $(".search_input").focusout(function() {
                        $('.dropdown').removeClass('open');
                });
                $(".genre").on("click", function(){
                    if($(this).hasClass("btn")) return;
                    $.redirect('result.php', {
                            title: this.innerText,
                            uri: this.getAttribute("uri"),
                            wiki: this.getAttribute("wiki"),
                            subject: this.getAttribute("subject")
                    });
                });
                $(".btn.genre").on("click", function(){
                    if(this.innerText == "Expand") this.innerText = "Collapse";
                    else this.innerText = "Expand";
                });
            });
        });    
    </script>
</body>

</html>