<!--
    Banner.vue
    @project cattery-mcbe
    @author lixworth <lixworth@outlook.com>
    @copyright cattery-mcbe
    @create 2021/6/11 19:30
-->
<template>
    <div>
        <v-img
            src="~@/assets/img.png"
            gradient="to top, rgba(0, 0, 1, 0), rgba(0, 0, 0, 0.33)"
            height="40vh"
        >
            <div class="fill-height mt-0 mt-md-n15 d-flex mx-auto">
                <v-row
                    justify="center"
                    align="center"
                    class="text-center"
                >
                    <v-col cols="12">
                        <div class="my-15 my-sm-0 mx-6 pa-4">
                            <div class="banner-title font-weight-black white--text">
                                Minecraft: Bedrock Edition
                            </div>
                            <p class="pt-5">记载 MCBE 与 MCPE 多个版本游戏客户端</p>
                            <v-btn outlined to="/" active-class="primary">主页</v-btn> {{'\u3000'}}
                            <v-btn outlined to="/about" active-class="primary">关于</v-btn> {{'\u3000'}}
                        </div>
                    </v-col>
                </v-row>

            </div>
        </v-img>
    </div>
</template>

<script>
export default {
    name: "Banner"
}
</script>

<style scoped>
.banner-title {
    font-size: clamp(1rem, 1rem + 5vw, 3rem);

}
</style>