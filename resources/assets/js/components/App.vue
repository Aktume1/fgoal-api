<template>
	<div class="m-grid m-grid--hor m-grid--root m-page">
		<Header></Header>

		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

			<left-aside></left-aside>

			<div class="m-grid__item m-grid__item--fluid m-wrapper">
				<router-view></router-view>
			</div>
		</div>
	</div>
</template>
<script>
	import Header from './Header/Header.vue';
	import LeftAside from './Menu/LeftAside.vue';
	export default {
	    name: 'App',
	    components: {
            Header,
            LeftAside
        }
	}
</script>
