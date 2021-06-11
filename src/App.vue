<template>
    <v-app>
<!--        <v-app-bar
            app
            elevation="0"
        >
            &lt;!&ndash; 侧边栏按钮 &ndash;&gt;
            <v-toolbar-title onclick="window.location.href = '/'" style="cursor:pointer">
               Cattery
            </v-toolbar-title>

            <v-progress-linear
                :active="this.$store.state.loading"
                indeterminate
                absolute
                bottom
                color="deep-purple accent-4"
            ></v-progress-linear>

            <v-spacer />

            <v-btn icon>
                <v-icon mdi>mdi-github</v-icon>
            </v-btn>


        </v-app-bar>-->
        <banner/>

        <v-main class="main-background">
            <router-view/>
        </v-main>
    </v-app>
</template>

<script>

import Banner from "./components/Banner";

export default {
    name: 'App',
    components:{
        'banner': Banner
    },
    data: () => ({
        barItem: {

        },
    }),
};
</script>

<style scoped>
.main-background {
    background-size: initial;
    background: rgb(0, 0, 0) url("~@/assets/img_2.png") repeat fixed;
}
</style>
