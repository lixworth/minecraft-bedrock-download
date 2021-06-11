<template>
    <v-container style="margin-top: -10vh">
        <v-row
            justify="center"
        >
            <v-col
                cols="12"
                sm="8"
                md="6"
                lg="6"
            >
                <v-card>
                    <v-card-title>
                        <span class="subtitle-1">选择下载版本</span>
                    </v-card-title>
                    <v-card-text>
                        <v-select
                            :items="versions"
                            label="Solo field"
                            solo
                        ></v-select>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

export default {
    name: 'Home',

    components: {
    },
    data: () => ({
        versions: null,
        items: ['Foo', 'Bar', 'Fizz', 'Buzz'],
    }),
    created() {
        this.axios.get("/api").then().catch((err) => {
           this.axios.get("/data.json").then((res) => {
               const data = res.data;
               var versions_data = [];
               data.forEach((item) => {
                   versions_data.push(item.version);
               });
               this.versions = versions_data;
           })
        });
    }

}
</script>
