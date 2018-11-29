<template>
    <header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
		<div class="m-container m-container--fluid m-container--full-height">
			<div class="m-stack m-stack--ver m-stack--desktop">
                <!-- BEGIN: Brand -->
                <brand></brand>
                <!-- END: Brand -->

                <!-- BEGIN: Menu -->
                <navbar></navbar>
                <!-- END: Menu -->
			</div>
		</div>
    </header>
</template>
<script>
    import Brand from './Brand.vue';
    import Navbar from './Navbar.vue';
    export default {
        name: 'Header',
        components: {
            Brand,
            Navbar
        }
    }
</script>
