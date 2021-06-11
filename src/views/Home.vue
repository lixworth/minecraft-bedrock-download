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
                <v-card :loading="loading" :disabled="loading">
                    <v-card-title>
                        <span class="subtitle-1">选择下载版本</span>
                    </v-card-title>
                    <v-card-text>
                        <v-radio-group
                            v-model="type"
                            row
                        >
                            <v-radio
                                label="所有版本"
                                value="all"
                            ></v-radio>

                            <v-radio
                                label="正式版本"
                                value="stable"
                            ></v-radio>
                            <v-radio
                                label="测试版本"
                                value="beta"
                            ></v-radio>
                        </v-radio-group>
                        <v-select
                            :items="versions"
                            :label="versions[0]"
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
        versions: [
            "正在拉取数据"
        ],
        type: "stable",
        loading: false
    }),
    created() {
        this.pull_data();
    },
    watch:{
        type(newVal,oldVal){
            this.pull_data();
        }
    },
    methods:{
        pull_data(){
            this.loading = true;
            this.axios.get("/api").then((res)=>{
                if(res.data.success){

                }else{
                    this.get_cache();
                }
            }).catch((err) => {
                this.get_cache();
            });
        },
        get_cache(){
            this.axios.get("/data.json").then((res) => {
                const data = res.data;
                var versions_data = [];
                data.forEach((item) => {
                    if(this.type == "all"){
                        if(item.beta){
                            versions_data.push(item.version+" [测试版]");
                        }else{
                            versions_data.push(item.version);
                        }
                    }else if(this.type == "stable"){
                        if(!item.beta){
                            versions_data.push(item.version);
                        }
                    }else if(this.type == "beta"){
                        if(item.beta){
                            versions_data.push(item.version);
                        }
                    }

                });
                this.versions = versions_data;
                this.loading = false;

            })
        }
    }

}
</script>
