<!-- Title bar -->
<div class="pi-section-w pi-section-base pi-section-base-gradient">
	<div class="pi-texture" style="background: url(img/hexagon.png) repeat;"></div>
	<div class="pi-section" style="padding: 30px 40px 26px;">
	
		<div class="pi-row">
			<div class="pi-col-sm-4 pi-center-text-xs">
				<h1 class="h2 pi-weight-300 pi-margin-bottom-5">{$contentType.name} </h1>
			</div>
			<!--<div class="pi-col-sm-8 pi-text-right pi-center-text-xs">
				<p class="lead-20 pi-weight-300 pi-margin-top-5 pi-margin-bottom-5">{$category.name}</p>
			</div>-->
		</div>
		
	</div>
</div>
<!-- End title bar -->



<!-- Breadcrumbs 
<div class="pi-section-w pi-border-bottom pi-section-grey">
	<div class="pi-section pi-titlebar pi-breadcrumb-only">
		<div class="pi-breadcrumb pi-center-text-xs">
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">Company</a></li>
				<li>Blog</li>
			</ul>
		</div>
	</div>
</div>-->




<div class="pi-section-w pi-section-white">
	<div class="pi-section pi-padding-bottom-10">
		
		<div class="pi-row">
		
			<div class="pi-col-sm-9">
				
				<!-- Post -->
				<div class="pi-row">
					
					<!-- Post thumbnail -->
					<div class="pi-col-sm-2">
						<div class="pi-img-w pi-img-round-corners pi-img-shadow">
							<a href="{site_url|con:'upload/content/entry/': $entry.image}" class="pi-colorbox">
								<img src="{site_url|con:'upload/content/entry/': $entry.image}" alt="">
								<div class="pi-img-overlay pi-no-padding pi-img-overlay-dark">
									<div class="pi-caption-centered">
										<div>
											<span class="pi-caption-icon pi-caption-icon-small pi-caption-scale icon-search"></span>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
					<!-- End post thumbnail -->
					
					<!-- Post content -->
					<div class="pi-col-sm-10">
						<h2 class="h3 pi-margin-top-minus-5">
							<a href="#" class="pi-link-dark">{$entry.title}</a>
						</h2>
					
						<p>
						{$entry.full_story}
						</p>
						<p>
						
						</p>
					</div>
					<!-- End post content -->
					
				</div>
				<!-- End post -->
				
				<hr class="pi-divider pi-divider-dashed pi-divider-big">
	
			</div>
		
			<div class="pi-sidebar pi-col-sm-3">
				
				<!-- Search -->
				<div class="pi-sidebar-block pi-padding-bottom-60">
					<h3 class="h6 pi-uppercase pi-weight-700 pi-letter-spacing pi-has-bg pi-margin-bottom-20">
						جستجو
					</h3>
					<form class="pi-grouped-input pi-pi-search-form-wide">
						<button type="submit" class="btn pi-btn-base"><i class="icon-search-1"></i></button>
						<div class="pi-input-inline">
							<input type="text" class="form-control pi-input-wide" placeholder="جستجو...">
						</div>
					</form>
				</div>
				<!-- End search -->
				
				<!-- Categories -->
				<div class="pi-sidebar-block pi-padding-bottom-40">
					<h3 class="h6 pi-uppercase pi-weight-700 pi-letter-spacing pi-has-bg pi-margin-bottom-15">
					برترین ها
					</h3>
					<ul class="pi-list-with-icons pi-list-icons-right-open">
						<li><a href="#">لورم ایپسوم متن</a></li>
						<li><a href="#">لورم ایپسوم متن</a></li>
						<li><a href="#">لورم ایپسوم متن</a></li>
						<li><a href="#">لورم ایپسوم متن</a></li>
						<li><a href="#">لورم ایپسوم متن</a></li>
				
					</ul>
				</div>
			
			</div>
			
		</div>
		
	</div>
</div>
