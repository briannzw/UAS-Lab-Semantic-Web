<?php
    require 'vendor/autoload.php';

    $uri = ""; $wiki=""; $subject = ""; $title = ""; $search_uri = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['title'])){
            $title = ($_POST['title'] != "") ? $_POST['title'] : "";
        }
        if(isset($_POST['uri'])){
            $uri = $_POST['uri'];
        }
        if(isset($_POST['wiki'])){
            $wiki = ($_POST['wiki'] != "") ? $_POST['wiki'] : $uri;
        }
        if(isset($_POST['subject'])){
            $subject = ($_POST['wiki'] != "") ? $_POST['subject'] : $uri;
        }
        if(isset($_POST['search_uri'])){
            $search_uri = ($_POST['search_uri'] != "") ? $_POST['search_uri'] : "";
        }
    }
    else{
        var_dump('POST Data not found!');
        header('Location: index.php');
        die;
    }

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

    $genre_list = $sparql->query($query);

    $sparql = new \EasyRdf\Sparql\Client('https://dbpedia.org/sparql');

    if($search_uri == "") : 
    $query = "
    SELECT DISTINCT ?game ?label ?abstract
    group_concat(DISTINCT ?developer;separator=',') as ?developers
    group_concat(DISTINCT ?publisher;separator=',') as ?publishers
    group_concat(DISTINCT ?release_date;separator=',') as ?release_dates
    group_concat(DISTINCT ?genre_uri;separator=',') as ?genre_uris
    group_concat(DISTINCT ?game_engine;separator=',') as ?game_engines
    group_concat(DISTINCT ?genre;separator=',') as ?genres
    WHERE{
        {
                ?game rdf:type dbo:VideoGame.
                ?game dbo:genre|dbp:genre <http://dbpedia.org/resource/".$uri.">.

                ?game rdfs:label ?label.
                ?game dbo:abstract ?abstract.

                OPTIONAL{?game dbo:releaseDate ?release_date.}
                
                OPTIONAL{?game dbo:developer ?developer_uri.
                         ?developer_uri rdfs:label ?developer.}

                OPTIONAL{?game dbo:publisher ?publisher_uri.
                         ?publisher_uri rdfs:label ?publisher.}

                ?game dbo:genre|dbp:genre ?genre_uri.
                ?genre_uri rdfs:label ?genre.

                OPTIONAL{?game dbo:gameEngine ?game_engine_uri.
                    ?game_engine_uri rdfs:label ?game_engine.}

        }
        UNION
        {
                ?game rdf:type dbo:VideoGame.
                ?game dbo:wikiPageWikiLink <http://dbpedia.org/resource/".$wiki.">.

                ?game rdfs:label ?label.
                ?game dbo:abstract ?abstract.

                OPTIONAL{?game dbo:releaseDate ?release_date.}
                
                OPTIONAL{?game dbo:developer ?developer_uri.
                         ?developer_uri rdfs:label ?developer.}

                OPTIONAL{?game dbo:publisher ?publisher_uri.
                         ?publisher_uri rdfs:label ?publisher.}

                ?game dbo:genre|dbp:genre ?genre_uri.
                ?genre_uri rdfs:label ?genre.

                OPTIONAL{?game dbo:gameEngine ?game_engine_uri.
                    ?game_engine_uri rdfs:label ?game_engine.}
        }
        UNION
        {
                ?game rdf:type dbo:VideoGame.
                <http://dbpedia.org/resource/".$wiki."> dbo:wikiPageWikiLink ?game.

                ?game rdfs:label ?label.
                ?game dbo:abstract ?abstract.

                OPTIONAL{?game dbo:releaseDate ?release_date.}
                
                OPTIONAL{?game dbo:developer ?developer_uri.
                         ?developer_uri rdfs:label ?developer.}

                OPTIONAL{?game dbo:publisher ?publisher_uri.
                         ?publisher_uri rdfs:label ?publisher.}

                ?game dbo:genre|dbp:genre ?genre_uri.
                ?genre_uri rdfs:label ?genre.

                OPTIONAL{?game dbo:gameEngine ?game_engine_uri.
                    ?game_engine_uri rdfs:label ?game_engine.}
        }
        UNION
        {
                ?game rdf:type dbo:VideoGame.
                ?game dct:subject <http://dbpedia.org/resource/Category:".$subject.">.

                ?game rdfs:label ?label.
                ?game dbo:abstract ?abstract.

                OPTIONAL{?game dbo:releaseDate ?release_date.}
                
                OPTIONAL{?game dbo:developer ?developer_uri.
                         ?developer_uri rdfs:label ?developer.}

                OPTIONAL{?game dbo:publisher ?publisher_uri.
                         ?publisher_uri rdfs:label ?publisher.}

                ?game dbo:genre|dbp:genre ?genre_uri.
                ?genre_uri rdfs:label ?genre.

                OPTIONAL{?game dbo:gameEngine ?game_engine_uri.
                         ?game_engine_uri rdfs:label ?game_engine.}
        }
        FILTER(LANG(?label) = 'en' || LANG(?label) = '').
        FILTER(LANG(?abstract) = 'en' || LANG(?abstract) = '').
        FILTER(LANG(?developer) = 'en' || LANG(?developer) = '').
        FILTER(LANG(?publisher) = 'en' || LANG(?publisher) = '').
        FILTER(LANG(?genre) = 'en' || LANG(?genre) = '').
        FILTER(LANG(?game_engine) = 'en' || LANG(?game_engine) = '').
    } GROUP BY ?game ?label ?abstract
    ";

    else : 
        $query = "
        SELECT DISTINCT ?game ?label ?abstract
        group_concat(DISTINCT ?developer;separator=',') as ?developers
        group_concat(DISTINCT ?publisher;separator=',') as ?publishers
        group_concat(DISTINCT ?release_date;separator=',') as ?release_dates
        group_concat(DISTINCT ?genre_uri;separator=',') as ?genre_uris
        group_concat(DISTINCT ?game_engine;separator=',') as ?game_engines
        group_concat(DISTINCT ?genre;separator=',') as ?genres
        WHERE{
            {
                    BIND (<http://dbpedia.org/resource/".$search_uri."> as ?game).
                    ?game rdfs:label ?label.
                    ?game dbo:abstract ?abstract.
    
                    OPTIONAL{?game dbo:releaseDate ?release_date.}
                    
                    OPTIONAL{?game dbo:developer ?developer_uri.
                             ?developer_uri rdfs:label ?developer.}
    
                    OPTIONAL{?game dbo:publisher ?publisher_uri.
                             ?publisher_uri rdfs:label ?publisher.}
    
                    ?game dbo:genre|dbp:genre ?genre_uri.
                    ?genre_uri rdfs:label ?genre.
    
                    OPTIONAL{?game dbo:gameEngine ?game_engine_uri.
                        ?game_engine_uri rdfs:label ?game_engine.}
    
            }
            FILTER(LANG(?label) = 'en' || LANG(?label) = '').
            FILTER(LANG(?abstract) = 'en' || LANG(?abstract) = '').
            FILTER(LANG(?developer) = 'en' || LANG(?developer) = '').
            FILTER(LANG(?publisher) = 'en' || LANG(?publisher) = '').
            FILTER(LANG(?genre) = 'en' || LANG(?genre) = '').
            FILTER(LANG(?game_engine) = 'en' || LANG(?game_engine) = '').
        } GROUP BY ?game ?label ?abstract
        ";
    endif;

    $result = $sparql->query($query);

    $result_count = $result->numRows();

    $result = iterator_to_array($result);

    //Pagination
    $dataPerHalaman = max(1, min(15, $result_count));
    $dataCount = $result_count;                                     //total data
    $jumlahHalaman = ceil($dataCount / $dataPerHalaman);
    $halamanAktif = isset($_POST['page']) ? $_POST['page'] : 1;       //page sekarang
    $indeksAwal = ($dataPerHalaman * ($halamanAktif - 1));

    $result = array_slice($result, $indeksAwal, $dataPerHalaman);   //data yang telah dipisah berdasarkan indeks halaman 1-3, 4-6

    function truncate($string,$length=100,$append="&hellip;") {
        $string = trim($string);

        if(strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }

        return $string;
    }
?>

<html>

<head>
    <title>Search Results : <?php if($title != "") : echo $title . " Games"; elseif($search_uri != "") : echo $result[0]->label; else : echo "[GENRE]"; endif; ?></title>
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
    <h1 class="title mt-5"><?php if($title != "") : echo $title . " Games"; elseif($search_uri != "") : echo $result[0]->label; else : echo "[GENRE]"; endif; ?></h1>
    
    <a class="btn main-container p-0 text-white" href="index.php"><?= "<" ?> Back to Index</a>

    <div class="main-container d-flex align-items-center mt-4 py-1 nav-bg">
        <div class="row card-title w-100 ps-4 my-auto">
            <span class="sub-title p-0">Search by Genre</span>
            <div class="collapse" id="genre-list">
            <div class="d-flex flex-wrap">
            <?php   foreach($genre_list as $row) :
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
        <p class="text-white mt-5">Showing <?= ($indeksAwal + 1)."-".min($indeksAwal + $dataPerHalaman, $dataCount)." of ". $result_count ?> result<?= ($result_count <= 1) ? "" : "s" ?></p>
    </div>

    <?php

    $i = 0;
    
    $detail = [];
    foreach($result as $row) : 
        $detail = [
            'game_uri'=> !($row->game)->isBNode() ? $row->game : "",
            'label'=> !empty(($row->label)->getValue()) ? $row->label : "-",
            'release_dates'=> !empty(($row->release_dates)->getValue()) ? $row->release_dates : "-",
            'developers'=> !empty(($row->developers)->getValue()) ? $row->developers : "-",
            'publishers'=> !empty(($row->publishers)->getValue()) ? $row->publishers : "-",
            'abstract'=> !empty(($row->abstract)->getValue()) ? $row->abstract : "-",
            'genres'=> !empty(($row->genres)->getValue()) ? $row->genres : "-",
            'genre_uris'=> !empty(($row->genre_uris)->getValue()) ? $row->genre_uris : "",
            'game_engines'=> !empty(($row->game_engines)->getValue()) ? $row->game_engines : "-",
        ];

        $detail['release_date'] = explode(",", $detail['release_dates'])[0];
        $detail['developers'] = explode(",", $detail['developers']);
        $detail['publishers'] = explode(",", $detail['publishers']);
        $detail['genres'] = explode(",", $detail['genres']);
        $detail['genre_uris'] = explode(",", $detail['genre_uris']);
        $detail['game_engines'] = explode(",", $detail['game_engines']);
        $detail['abstract'] = truncate($detail['abstract'], 500);

        \EasyRdf\RdfNamespace::setDefault('og');

        $detail["game_uri"] = str_replace("http://dbpedia.org/resource/", "", $detail["game_uri"]);
        $wiki_uri = 'https://en.wikipedia.org/wiki/' . $detail["game_uri"];
        $wiki_img = \EasyRdf\Graph::newAndLoad($wiki_uri);

        $i++;
        
        //break for pagination
    ?>
    <?php if($i == 1) : ?>
        <div class="card mb-5" style="display:flex; position:relative; left:10rem; margin-right:20rem;">
    <?php elseif($i == $dataPerHalaman) : ?>
        <div class="card mb-3" style="display:flex; position:relative; left:10rem; margin-right:20rem;">
    <?php else : ?>
        <div class="card my-5" style="display:flex; position:relative; left:10rem; margin-right:20rem;">
    <?php endif; ?>
            <div class="row no-gutters">
                <div class="col">
                    <!--awal carousel-->
                    <div id="Carousel-<?= $i ?>" class="carousel slide carousel-fade" data-bs-ride="carousel" data-pause="hover">
                        <div class="carousel-inner ratio ratio-16x9">
                            <div class="carousel-item active">
                                <img src="<?= ($wiki_img->image) ? $wiki_img->image : 'img/default.png' ?>" class="d-block w-100" alt="..." />
                            </div>
                        </div>
                    </div>
                    <!--akhir carousel-->
                </div>
                <div class="col-md-4">
                    <div class="card-body p-0">
                        <h5 class="card-title mt-2 me-3">
                            <?= $detail["label"] ?>
                        </h5>
                        <p class="card-text text-muted ">RELEASE DATE: &#160;&#160;<span class="txt-gray">
                                <?= $detail["release_date"] ?>
                            </span></p>
                        <?php foreach($detail['developers'] as $developer) : ?>
                            <p class="card-text text-muted m-0">DEVELOPER: &#160;&#160;&#160;&#160;&#160;&#160;<span class="txt-gray">
                                    <?= $developer ?>
                                </span></p>
                        <?php endforeach; ?>
                        <?php
                            $j = 0;
                            $size = count($detail['publishers']);
                            foreach($detail['publishers'] as $publisher) : $j++;?>
                            <p class="card-text text-muted <?= ($j == $size) ? "" : "m-0"?>">PUBLISHER: &#160;&#160;&#160;&#160;&#160;&#160;&#160;<span class="txt-gray">
                                    <?= $publisher ?>
                                </span></p>
                        <?php endforeach; ?>
                        <?php 
                            $j = 0;
                            $size = count($detail['game_engines']);
                            foreach($detail['game_engines'] as $game_engine) : $j++; ?>
                            <p class="card-text text-muted <?= ($j == $size) ? "" : "m-0"?>">GAME ENGINE: &#160;<span class="txt-gray">
                                    <?= $game_engine ?>
                                </span></p>
                        <?php endforeach; ?>
                        <p class="card-text me-3"><span class="txt-gray">
                                <?= $detail["abstract"] ?>
                            </span></p>
                        <div class="card-text">
                            <div class="row mx-0">
                                <?php 
                                $j = 0;
                                foreach($detail["genres"] as $genre) :
                                    $detail['genre_uris'][$j] = str_replace("http://dbpedia.org/resource/", "", $detail['genre_uris'][$j]);
                                ?>
                                    <div class="col-auto genre mb-1" uri=<?= $detail["genre_uris"][$j] ?>>
                                        <?= $genre ?>
                                    </div>
                                <?php 
                                    $j++;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="container pb-1"></div>

    <!--Pagination-->
    <?php if ($result_count > 0) : ?>
        <nav class="main-container nav-bg text-right">
            <ul class="pagination justify-content-center m-0">
                <?php if ($halamanAktif <= 1) : ?>
                    <li class="page-item disabled item-nav">
                        <span class="page-link"><?= "<" ?></span>
                    </li>
                <?php else : ?>
                    <li class="page-item item-nav">
                        <a class="page-link" page=<?= ($halamanAktif - 1) ?>><?= "<" ?></a>
                    </li>
                <?php endif; ?>

                <?php   if($jumlahHalaman > 7) : 
                        if($halamanAktif <= 4) : 

                            for ($i = 1; $i <= 6; $i++) : 
                                if($i == $halamanAktif) : ?>
                                    <li class="page-item active">
                                        <span class="page-link">
                                            <?= $halamanAktif ?>
                                        </span>
                                    </li>
                <?php           else : ?>
                                    <li class="page-item">
                                        <a class="page-link" page=<?php echo $i;?>> <?php echo $i; ?> </a>
                                    </li>
                <?php           endif;
                            endfor; ?>
                        <li class="page-item">
                            <span class="page-dot">...</span>
                        </li>
                        <li class="page-item">
                            <a class="page-link" page=<?php echo $jumlahHalaman;?>> <?php echo $jumlahHalaman; ?> </a>
                        </li>

                <?php   elseif ($halamanAktif > $jumlahHalaman - 4) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo "1"?>"> <?php echo "1" ?> </a>
                        </li>
                        <li class="page-item">
                            <span class="page-dot">...</span>
                        </li>
                <?php       for($i = ($jumlahHalaman - 5); $i <= $jumlahHalaman; $i++) :
                                if($i == $halamanAktif) : ?>
                                    <li class="page-item active">
                                        <span class="page-link">
                                            <?= $halamanAktif ?>
                                        </span>
                                    </li>
                <?php           else : ?>
                                    <li class="page-item">
                                        <a class="page-link" page=<?php echo $i;?>> <?php echo $i; ?> </a>
                                    </li>
                <?php           endif;
                            endfor; ?>

                <?php   else : ?>
                        <li class="page-item">
                            <a class="page-link" page=<?php echo "1"?>> <?php echo "1" ?> </a>
                        </li>
                        <li class="page-item">
                            <span class="page-dot">...</span>
                        </li>

                <?php       for($i = $halamanAktif - 2; $i <= $halamanAktif + 2; $i++) :
                                if($i == $halamanAktif) : ?>
                                    <li class="page-item active">
                                        <span class="page-link">
                                            <?= $halamanAktif ?>
                                        </span>
                                    </li>
                <?php           else : ?>
                                    <li class="page-item">
                                        <a class="page-link" page=<?php echo $i;?>> <?php echo $i; ?> </a>
                                    </li>
                <?php           endif;
                            endfor; ?>

                        <li class="page-item">
                            <span class="page-dot">...</span>
                        </li>
                        <li class="page-item">
                            <a class="page-link" page=<?php echo $jumlahHalaman;?>> <?php echo $jumlahHalaman; ?> </a>
                        </li>
                <?php   endif;
                        else : 
                        for($i = 1; $i <= $jumlahHalaman; $i++) :
                                if($i == $halamanAktif) : ?>
                                    <li class="page-item active">
                                        <span class="page-link">
                                            <?= $halamanAktif ?>
                                        </span>
                                    </li>
                <?php           else : ?>
                                    <li class="page-item">
                                        <a class="page-link" page=<?php echo $i;?>> <?php echo $i; ?> </a>
                                    </li>
                <?php           endif;
                            endfor; ?>
                <?php   endif;
                ?>

                <?php if ($halamanAktif < $jumlahHalaman) : ?>
                    <li class="page-item item-nav">
                        <a class="page-link" page=<?php echo $halamanAktif + 1;?>><?= ">" ?></a>
                    </li>
                <?php else : ?>
                    <li class="page-item disabled item-nav">
                        <span class="page-link"><?= ">" ?></span>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            </ul>
        </nav>
    <!-- Akhir Pagination -->

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
                $(".page-link").click(function(){
                    if(typeof($(this).attr("page")) != "undefined" && $(this).attr("page") !== null){
                        $.redirect('result.php', {
                                title: <?= "'".$title."'" ?>,
                                uri: <?= "'".$uri."'" ?>,
                                wiki: <?= "'".$wiki."'" ?>,
                                subject: <?= "'".$subject."'" ?>,
                                page: $(this).attr("page")
                        });
                    }
                });
            });
        });    
    </script>
</body>

</html>