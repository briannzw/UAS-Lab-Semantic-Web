<?php
    require 'vendor/autoload.php';

    //Pagination
    $dataPerHalaman = 3;
    $dataCount = 0;                                             //total data
    $jumlahHalaman = ceil($dataCount / $dataPerHalaman);
    $halamanAktif = isset($_GET['page']) ? $_GET['page'] : 1;   //page sekarang
    $indeksAwal = ($dataPerHalaman * ($halamanAktif - 1));

    $result = [];                                                 //data yang telah dipisah berdasarkan indeks halaman 1-3, 4-6
?>

<html>

<head>
    <title>Search Results</title>
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
    <h1 class="title mt-5">[Genre]</h1>


    <div class="main-container d-flex align-items-center mt-4 py-1 nav-bg">
        <div class="row card-title w-100 ps-4 my-auto">
            <span class="sub-title p-0">Search by Genre</span>
            <div class="col-auto genre">RPG</div>
            <div class="col-auto genre">Action RPG</div>
            <div class="col-auto genre">Shooter</div>
        </div>
        <div class="me-2">
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
        <p class="text-white mt-5">0 Search Result</p>
    </div>

    <?php
    foreach ($result as $item) :
    ?>
        <div class="card my-5" style="display:flex; position:relative; left:10rem; margin-right:20rem;">
            <div class="row no-gutters">
                <div class="col">
                    <!--awal carousel-->
                    <div id="Carousel-<?= $i ?>" class="carousel slide carousel-fade" data-bs-ride="carousel" data-pause="hover">
                        <div class="carousel-indicators">
                            <?php /*<xsl:for-each select="links/*">
                                <xsl:choose>
                                    <xsl:when test="position()=1">
                                        <button type="button" data-bs-target="#Carousel-{$main-pos}" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <button type="button" data-bs-target="#Carousel-{$main-pos}" data-bs-slide-to="{position()-1}" aria-label="Slide {position()}"></button>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:for-each>
                            */ ?>
                        </div>
                        <div class="carousel-inner ratio ratio-16x9">
                            <?php /*<xsl:for-each select="links">
                                <xsl:variable name="video-count" select="count(video)" />
                                <xsl:for-each select="video">
                                    <xsl:choose>
                                        <xsl:when test="position()='1'">
                                            <div class="carousel-item active">
                                                <iframe src="{.}" width="100%" height="100%"></iframe>
                                            </div>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <div class="carousel-item">
                                                <iframe src="{.}" width="100%" height="100%"></iframe>
                                            </div>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </xsl:for-each>
                                <xsl:for-each select="image">
                                    <xsl:choose>
                                        <xsl:when test="position()='1' and $video-count = 0">
                                            <div class="carousel-item active">
                                                <img src="{.}" class="d-block w-100" alt="..." />
                                            </div>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <div class="carousel-item">
                                                <img src="{.}" class="d-block w-100" alt="..." />
                                            </div>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </xsl:for-each>
                            </xsl:for-each>
                            */ ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#Carousel-{position()}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#Carousel-{position()}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <!--akhir carousel-->
                </div>
                <div class="col-md-4">
                    <div class="card-body p-0">
                        <img src="{cover}" class="d-block w-100" alt="..." />
                        <h5 class="card-title mt-2">
                            <xsl:value-of select="name" />
                        </h5>
                        <p class="card-text text-muted ">RELEASE DATE: <span class="txt-gray">
                                <xsl:value-of select="release_date" />
                            </span></p>
                        <xsl:for-each select="developer">
                            <p class="card-text text-muted m-0">DEVELOPER: &#160;&#160;&#160;&#160;&#160;<span class="txt-gray">
                                    <xsl:value-of select="." />
                                </span></p>
                        </xsl:for-each>
                        <p class="card-text text-muted">PUBLISHER: &#160;&#160;&#160;&#160;&#160;&#160;<span class="txt-gray">
                                <xsl:value-of select="publisher" />
                            </span></p>
                        <div class="card-text">
                            <div class="row mx-0">
                                <xsl:for-each select="genre">
                                    <div class="col-auto genre">
                                        <xsl:value-of select="." />
                                    </div>
                                </xsl:for-each>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!--Pagination-->
    <?php if (count($result) > 0) : ?><!--
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($halamanAktif <= 1) : ?>
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                <?php else : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= ($halamanAktif - 1) ?>">&laquo;</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <?php if ($i == $halamanAktif) : ?>
                        <li class="page-item active">
                            <span class="page-link">
                                <?= $halamanAktif ?>
                            </span>
                        </li>
                    <?php else : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $i;
                                                                if (isset($_GET['cari'])) {
                                                                    echo "&keyword=" . $_GET['keyword'] . "&tipe_bangunan=" . $_GET['tipe_bangunan'] . "&cari=true";
                                                                }
                                                                ?>"> <?php echo $i; ?> </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($halamanAktif < $jumlahHalaman) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $halamanAktif + 1;
                                                            if (isset($_GET['cari'])) {
                                                                echo "&keyword=" . $_GET['keyword'] . "&tipe_bangunan=" . $_GET['tipe_bangunan'] . "&cari=true";
                                                            }
                                                            ?>">&raquo;</a>
                    </li>
                <?php else : ?>
                    <li class="page-item disabled">
                        <span class="page-link">&raquo;</span>
                    </li>
                <?php endif; ?>-->
            <?php endif; ?>
            </ul>
        </nav>
    <!-- Akhir Pagination -->

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
            });
        });    
    </script>
</body>

</html>