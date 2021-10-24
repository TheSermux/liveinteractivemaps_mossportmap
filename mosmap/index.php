<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Интерактивная карта размещения спортивной инфраструктуры города Москва</title>

    <!-- Favicons -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Vendor CSS (Icon Font) -->

    
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	

    <link rel="stylesheet" href="assets/css/vendor/vendor.min.css">
    <link rel="stylesheet" href="assets/css/plugins/plugins.min.css">
    <link rel="stylesheet" href="assets/css/style.min.css">
	
	
	


<style>
#map {
width: 100%; /* Ширина карты */
height: 600px; /* Высота карты */ 
}
.info-window h4 {
font-family: 'Tahoma'; /* Шрифт заголовка описания */
color: #694198; /* Цвет заголовка описания */
}
.info-content {
color: #777; /* Цвет контента описания */
}

.section-margin {
    margin-top: 0px !important; 
    margin-bottom: 0px  !important; 
}




.preloader {
  /*фиксированное позиционирование*/
  position: fixed;
  /* координаты положения */
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  /* фоновый цвет элемента */
  background: #e0e0e0;
  /* размещаем блок над всеми элементами на странице (это значение должно быть больше, чем у любого другого позиционированного элемента на странице) */
  z-index: 1001;
}

.preloader__row {
  position: relative;
  top: 50%;
  left: 50%;
  width: 70px;
  height: 70px;
  margin-top: -35px;
  margin-left: -35px;
  text-align: center;
  animation: preloader-rotate 2s infinite linear;
}

.preloader__item {
  position: absolute;
  display: inline-block;
  top: 0;
  background-color: #CC2222; /*#337ab7;*/
  border-radius: 100%;
  width: 35px;
  height: 35px;
  animation: preloader-bounce 2s infinite ease-in-out;
}

.preloader__item:last-child {
  top: auto;
  bottom: 0;
  animation-delay: -1s;
}

@keyframes preloader-rotate {
  100% {
    transform: rotate(360deg);
  }
}

@keyframes preloader-bounce {

  0%,
  100% {
    transform: scale(0);
  }

  50% {
    transform: scale(1);
  }
}

.loaded_hiding .preloader {
  transition: 0.3s opacity;
  opacity: 0;
}

.loaded .preloader {
  display: none;
}

</style>

</head>

<body>
<div class="preloader">
  <div class="preloader__row">
    <div class="preloader__item"></div>
    <div class="preloader__item"></div>
  </div>
</div>



	
	 

    <!-- Header Section Start -->
    <div class="header section">

        <!-- Header Top Start -->
        <div class="header-top bg-name-primary">
            <div class="container">
                <div class="row align-items-center">

                    <!-- Header Top Message Start -->
                    <div class="col-12 col-lg-10">
                        <div class="header-top-msg-wrapper">
                            <p class="header-top-message"><b>Интерактивная карта для размещения спортивной инфраструктуры</b></p>
                        </div>
                    </div>
                    
                    <!-- Header Top Message End -->

                </div>
            </div>
        </div>
        <!-- Header Top End -->

        <!-- Header Bottom Start -->
        <div class="header-bottom">
            <div class="header-sticky">
                <div class="container">
                    <div class="row align-items-center position-relative">

                        <!-- Header Logo Start -->
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="header-logo">
                                <!--<a href="index.html"><img src="assets/images/logo/logo.png" alt="Site Logo" /></a>-->
                            </div>
                        </div>
                        <!-- Header Logo End -->

                        <!-- Header Menu Start -->
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="main-menu">
                                <ul>
                                    
                                    <li><a href="index.html">Главная</a></li>
                                    <!--li><a href="#">О карте</a></li-->
									
									<li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Показатели</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Header Menu End -->



                    </div>
                </div>
            </div>
        </div>
        <!-- Header Bottom End -->

    </div>
    <!-- Header Section End -->
	

	<div class="section section-margin h-100">
        <div class="container-fluid h-100">
			<div class="row flex-row-reverse h-100">
				<div  class="col-lg-10 col-12 h-75">
					<div id="map" class="row row-cols-lg-1 row-cols-sm-1 row-cols-1 m-b-n100 h-100">
				
					</div>
				</div>
				<div class="col-lg-2 col-12">
                    <!-- Sidebar Widget Start -->
                    <aside class="sidebar_widget m-t-50 mt-lg-0">
                        <div class="widget_inner">
						
							<div class="widget-list m-b-50"><center>			
								<h3 class="widget-title m-b-30">Фильтры</h3>
                                <div class="sidebar-body">
									<form>
										<div class="form-group">
											<label for="stitle">Наименование спортивного объекта</label>
											<input type="text" class="form-control" id="stitle">
											<br>
										</div>
										<div class="form-group">
											<label for="avail">Доступность</label>
											<select class="form-control" id="avail">
											</select>
											<br>
										</div>
										<div class="form-group">
											<label for="dep">Ведомственная принадлежность</label>
											<select class="form-control" id="dep">
											</select>
											<br>
										</div>
										<div class="form-group">
											<label for="szones">Наименовани спортивных зон</label>
											<select class="form-control" id="szones">
											</select>
											<br>
										</div>
										<div class="form-group">
											<label for="tszones">Типы спортивных зон</label>
											<select class="form-control" id="tszones">
											</select>
											<br>
										</div>
										<div class="form-group">
											<button class="btn btn-primary btn-hover-dark avsbtn">Показать</button>
										</div>
										
									</form>
                                    
                                </div>
                            </center></div>
						
						</div>
					</aside>
				</div>
			</div>	
				
		</div>
		
	</div>
	


    <!-- Footer Section Start -->
    <footer class="section footer-section">
        

        <!-- Footer Bottom Start -->
        <div class="footer-bottom bg-name-light p-t-20 p-b-20">
            <div class="container">
                <div class="row align-items-center m-b-n20">
                    <div class="col-md-6 text-center text-md-start order-2 order-md-1 m-b-20">
                        <div class="copyright-content">
                            <p class="mb-0">© 2021 <strong>LiveInteractiveMaps</strong> <br>в рамках конкурса Лидеры цифровой трансформации</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- Footer Bottom End -->
    </footer>
    <!-- Footer Section End -->



    <!-- Scroll Top Start -->
    <a href="#" class="scroll-top show" id="scroll-top">
        <i class="arrow-top ti-angle-double-up"></i>
        <i class="arrow-bottom ti-angle-double-up"></i>
    </a>
    <!-- Scroll Top End -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Показатели</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body row">
        <div class="col-md-3"><h3>Общая площадь спортивных зон</h3></div>
		<div class="col-md-3"><h3>Площадь спортивных зон на 100 000 человек</h3></div>
		<div class="col-md-3"><h3>Количество спортивных зон</h3></div>
		<div class="col-md-3"><h3>Виды спортивных услуг</h3></div>

		<div class="col-md-3"><h3><br><br><a style="color:red; text-align: center " id="pok1">3 676 601</a></h3></div>
		<div class="col-md-3"><h3><br><br><a style="color:red; text-align: center" id="pok2">3 585</a></h3></div>
		<div class="col-md-3"><h3><br><br><a style="color:red; text-align: center"  id="pok3">100</a></h3></div>
		<div class="col-md-3"><h3><br><br><a style="color:red; text-align: center"  id="pok4">200</a></h3></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

    <!-- Global Vendor, plugins JS -->

    <!-- Vendor JS -->


    <!-- 
    <script src="assets/js/vendor/popper.min.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <script src="assets/js/vendor/modernizr-3.11.2.min.js"></script>   
    -->


    <!-- Plugins JS -->


    <!-- 
    <script src="assets/js/plugins/aos.min.js"></script>
    <script src="assets/js/plugins/jquery.ajaxchimp.min.js"></script>
    <script src="assets/js/plugins/jquery-ui.min.js"></script>
    <script src="assets/js/plugins/nice-select.min.js"></script>
    <script src="assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="assets/js/plugins/countdown.min.js"></script>
    <script src="assets/js/plugins/lightgallery-all.min.js"></script>  
    -->


    <!-- Use the minified version files listed below for better performance and remove the files listed above -->

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/plugins.min.js"></script>

    <!--Main JS-->
    <script src="assets/js/main.js"></script>
	<script>
		var geocoder;
		var map;
		var polygonArray = [];
		var circles_onmap;
		var markers_onmap;
		var infowindows;
		
		function initMap() {
			map = new google.maps.Map(document.getElementById("map"), {
				zoom: 11,
				center: { lat: 55.753210, lng: 37.619055 },
				mapTypeId: "roadmap",
			  });
			  
			const drawingManager = new google.maps.drawing.DrawingManager({
				//drawingMode: google.maps.drawing.OverlayType.MARKER,
				drawingControl: true,
				drawingControlOptions: {
				position: google.maps.ControlPosition.TOP_CENTER,
				drawingModes: [
					//google.maps.drawing.OverlayType.MARKER,
					//google.maps.drawing.OverlayType.CIRCLE,
					google.maps.drawing.OverlayType.POLYGON,
					//google.maps.drawing.OverlayType.POLYLINE,
					//google.maps.drawing.OverlayType.RECTANGLE,
				],
				},
				/*markerOptions: {
					icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
				},
				circleOptions: {
					fillColor: "#ffff00",
					fillOpacity: 1,
					strokeWeight: 5,
					clickable: false,
					editable: true,
					zIndex: 1,
				},*/
			});

			drawingManager.setMap(map);
			
			google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
				//alert(123)			
			});
		}	
		
	
	$(document).ready(function(){
	
	
		jQuery(".avsbtn").on("click", function(){
			document.body.classList.remove('loaded_hiding');
			document.body.classList.remove('loaded');
			
			var prms = {};
			
			stitle_val = jQuery("#stitle").val();
			
			if(stitle_val.length > 0) {
				prms.name = stitle_val;	
			}
			
			
			dep_val = jQuery("#dep").val();
			
			if(dep_val > 0) {
				prms.id_deps = dep_val;	
			}
			
			
			avail_val = jQuery("#avail").val();
			
			if(avail_val > 0) {
				prms.id_avs = avail_val;	
			}
			else {
				prms.id_avs = 1;	
			}
			
			
			szones_val = jQuery("#szones").val();
			
			if(szones_val > 0) {
				prms.id_zts = szones_val;	
			}
			
			
			
			
			prms.limit = 1000;	
			prms.load_refs = true;
			
			//console.log(prms);
			
			getDataByAjax(prms);
			
		})
	
		
		/*
		var prms = {
			name: "",
			zone_name: "",
			load_refs: false,
			id_avs: 1,
			id_deps: 0,
			id_zts: 0,
			id_sts: 0,
			limit: 1000,
		};
		*/
		
		function getDataByAjax(prms, ref = false) {	
		
			jQuery
				.get('https://biomdi.activetest.tech/mosmapapi/', prms)
				.done(function( data ) {
					
					
					if(ref) {
						//jQuery("#pok1").append(data["reference_data"]["analytics"]["sum_zone_area_m2"]);
						//jQuery("#pok2").append(data["reference_data"]["analytics"]["sum_zone_area_per_100k"]);
						//jQuery("#pok3").append(data["reference_data"]["analytics"]["count_zones"]);
						//jQuery("#pok4").append(data["reference_data"]["analytics"]["zone_type_count"]);
						
						
						deparray = data["reference_data"]["departments"];
						
						//загрузка выпадющего списка принадлежности
						$("#dep").empty().append("<option value=0>Все</option>");
						
						for (dep_i in deparray) {                        
							$("#dep").append("<option value=" + deparray[dep_i].id_dep + ">" + deparray[dep_i].name + "</option>");
						}
						
						availarray = data["reference_data"]["availabilities"];
						
						//загрузка выпадющего списка принадлежности
						$("#avail").empty();
						
						for (avail_i in availarray) {                        
							$("#avail").append("<option value=" + availarray[avail_i].id_av + ">" + availarray[avail_i].name + "</option>");
						}
						
						szones = data["reference_data"]["zone_types"];
						
						//загрузка выпадющего списка принадлежности
						$("#szones").empty().append("<option value=0>Все</option>");
						
						for (szones_i in szones) {                        
							$("#szones").append("<option value=" + szones[szones_i].id_zt + ">" + szones[szones_i].name + "</option>");
						}
						
					}
					
					paintCircles(data["facilities"]);
					
					document.body.classList.add('loaded');
				  });
		}
		
		getDataByAjax({ load_refs: true, id_avs: 1, limit: 1000 }, true);
		//jQuery(".avsbtn").click();
			
	// MAP		
		
	function removeCircles() {
		for(const o1 in circles_onmap) {
			circles_onmap[o1].setMap(null);
		}
		
		for(const o2 in circles_onmap) {
			markers_onmap[o2].setMap(null);
		}
		
		for(const o3 in infowindows) {
			infowindows[o3].setMap(null);
		}
		
	}
	
	
	function paintCircles(pois) {
		removeCircles(); 
		
		circles_onmap = [];
		markers_onmap = [];
		infowindows = [];
		
		 for (const o in pois){
			circle_tmp = null;
			
			var centerCircle = { lat: pois[o].lat, lng: pois[o].lng };
			  
            circle_tmp = new google.maps.Circle({
              strokeColor: "#20c997",
              strokeOpacity: 0.3,
              strokeWeight: 0,
              //fillColor: "#20c997",
			  fillColor: pois[o].area_color,
              fillOpacity: 0.2,
              map: map,
              center: centerCircle,
			  radius: pois[o].radius,
            });
			
			var content_html = "<div><p>" +
				"<h2><b>Название</b>: " + pois[o].name + "</h2><br>" +
				"<b>Адрес</b>: " + pois[o].addr + "<br>" +
				"<b>Департамент</b>: " + pois[o].dep + "<br>" +
				"<b>Доступность</b>: " + pois[o].av + "<br>" +
				"</p>" +
				"<p> Зоны: <br>";
				
				
			for(const oz in pois[o].zones) {
				
				content_html = content_html + "<li>" +
					pois[o].zones[oz].name + " (тип: " + pois[o].zones[oz].type + "; ";
				
				content_html = content_html +
					"виды спорта: ";
				
				for(const ost in pois[o].zones[oz].sport_types) {
					content_html = content_html +
						pois[o].zones[oz].sport_types[ost].st + ", "; 
				}
				
				content_html = content_html +
					")</li>";
			}	
				
			content_html = content_html +
				"<div>";

			const infowindow_tmp = new google.maps.InfoWindow({
				content: content_html,
			});
			
			const svgMarker = {
				path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
				fillColor: "red",
				fillOpacity: 0.4,
				strokeWeight: 1,
				rotation: 0,
				scale: 3,
				anchor: new google.maps.Point(0, 0),
			};
			
			const marker_tmp = new google.maps.Marker({
				position: centerCircle,
				map,
				title: pois[o].name,
				icon: svgMarker,
			});

			marker_tmp.addListener("click", () => {
				for(const wo in infowindows) {
					infowindows[wo].close();
				}
				
				infowindow_tmp.open({
					anchor: marker_tmp,
					map: map,
					shouldFocus: false,
				});
				
			});
			
			markers_onmap.push(marker_tmp);
			infowindows.push(infowindow_tmp);
			circles_onmap.push(circle_tmp);
		
		  }
		}
	});
	
          

	
	
	
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCnnkvyRUOQsBzY4BBvgHSVoMTXxZWPdIU&callback=initMap&libraries=geometry,drawing&v=weekly" async ></script>
	
	
</body>

</html>