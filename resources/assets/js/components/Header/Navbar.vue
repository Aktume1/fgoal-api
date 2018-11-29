<template>
	<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

		<!-- BEGIN: Horizontal Menu -->
		<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
			<i class="la la-close"></i>
		</button>
		<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
			<ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
				<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click"
				 m-menu-link-redirect="1" aria-haspopup="true">
					<a href="javascript:;" class="m-menu__link m-menu__toggle">
						<i class="m-menu__link-icon flaticon-add"></i>
						<span class="m-menu__link-text">Actions</span>
						<i class="m-menu__hor-arrow la la-angle-down"></i>
						<i class="m-menu__ver-arrow la la-angle-right"></i>
					</a>
					<div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
						<span class="m-menu__arrow m-menu__arrow--adjust"></span>
						<ul class="m-menu__subnav">
							<li class="m-menu__item " aria-haspopup="true">
								<a href="header/actions.html" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-file"></i>
									<span class="m-menu__link-text">Create New Post</span>
								</a>
							</li>

						</ul>
					</div>
				</li>
			</ul>
		</div>

		<!-- END: Horizontal Menu -->

		<!-- BEGIN: Topbar -->
		<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
			<div class="m-stack__item m-topbar__nav-wrapper">
				<ul class="m-topbar__nav m-nav m-nav--inline">
					<li class="m-nav__item m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width m-dropdown--skin-light	m-list-search m-list-search--skin-light"
					 m-dropdown-toggle="click" id="m_quicksearch" m-quicksearch-mode="dropdown" m-dropdown-persistent="1">
						<a href="#" class="m-nav__link m-dropdown__toggle">
							<span class="m-nav__link-icon">
								<i class="flaticon-search-1"></i>
							</span>
						</a>
						<div class="m-dropdown__wrapper">
							<span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
							<div class="m-dropdown__inner ">
								<div class="m-dropdown__header">
									<form class="m-list-search__form">
										<div class="m-list-search__form-wrapper">
											<span class="m-list-search__form-input-wrapper">
												<input id="m_quicksearch_input" autocomplete="off" type="text" name="q" class="m-list-search__form-input"
												 value="" placeholder="Search...">
											</span>
											<span class="m-list-search__form-icon-close" id="m_quicksearch_close">
												<i class="la la-remove"></i>
											</span>
										</div>
									</form>
								</div>
								<div class="m-dropdown__body">
									<div class="m-dropdown__scrollable m-scrollable" data-scrollable="true" data-height="300" data-mobile-height="200">
										<div class="m-dropdown__content">
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>

					<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
					 m-dropdown-toggle="click">
						<a href="#" class="m-nav__link m-dropdown__toggle">
							<span class="m-topbar__userpic">
								<img src="assets/template/page/app/media/img/users/user4.jpg" class="m--img-rounded m--marginless" alt="" />
							</span>
							<span class="m-topbar__username m--hide">Nick</span>
						</a>
						<div class="m-dropdown__wrapper">
							<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
							<div class="m-dropdown__inner">
								<div class="m-dropdown__header m--align-center" style="background: url(assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
									<div class="m-card-user m-card-user--skin-dark">
										<div class="m-card-user__pic">
											<img src="assets/template/page/app/media/img/users/user4.jpg" class="m--img-rounded m--marginless" alt="" />

											<!--
																<span class="m-type m-type--lg m--bg-danger"><span class="m--font-light">S<span><span>
																-->
										</div>
										<div class="m-card-user__details">
											<span class="m-card-user__name m--font-weight-500">Mark Andre</span>
											<a href="" class="m-card-user__email m--font-weight-300 m-link">mark.andre@gmail.com</a>
										</div>
									</div>
								</div>
								<div class="m-dropdown__body">
									<div class="m-dropdown__content">
										<ul class="m-nav m-nav--skin-light">
											<li class="m-nav__section m--hide">
												<span class="m-nav__section-text">Section</span>
											</li>
											<li class="m-nav__item">
												<a href="header/profile.html" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-profile-1"></i>
													<span class="m-nav__link-title">
														<span class="m-nav__link-wrap">
															<span class="m-nav__link-text">My Profile</span>
															<span class="m-nav__link-badge">
																<span class="m-badge m-badge--success">2</span>
															</span>
														</span>
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="header/profile.html" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-share"></i>
													<span class="m-nav__link-text">Activity</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="header/profile.html" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
													<span class="m-nav__link-text">Messages</span>
												</a>
											</li>
											<li class="m-nav__separator m-nav__separator--fit">
											</li>
											<li class="m-nav__item">
												<a href="header/profile.html" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-info"></i>
													<span class="m-nav__link-text">FAQ</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="header/profile.html" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-lifebuoy"></i>
													<span class="m-nav__link-text">Support</span>
												</a>
											</li>
											<li class="m-nav__separator m-nav__separator--fit">
											</li>
											<li class="m-nav__item">
												<a href="snippets/pages/user/login-1.html" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">Logout</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</li>

				</ul>
			</div>
		</div>

		<!-- END: Topbar -->
	</div>
</template>
<script>
	export default {
	    name: 'Navbar'
	}
</script>
