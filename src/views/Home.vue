<template>
    <v-container style="margin-top: -95px">
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

                        <v-spacer></v-spacer>
                        <v-switch
                            v-model="only_download"
                            label="仅显示可下载"
                            color="primary"
                            hide-details
                        ></v-switch>
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
                            <v-radio
                                label="仅 Pocket Edition (便携版 0.x - 1.1.x)"
                                value="pe"
                            ></v-radio>
                        </v-radio-group>
                        <v-select
                            v-model="marjor_version"
                            :items="marjor_versions"
                            menu-props="auto"
                            label="选择大版本 （可选）"
                            hide-details
                            solo
                        ></v-select>
                        <br>
                        <v-select
                            :items="versions"
                            :label="versions[0]"
                            v-model="select_version"
                            menu-props="auto"
                            solo
                        ></v-select>
                        <p>大版本: {{ (this.info.pe)? "便携版" : "基岩版"}} {{this.info.marjor}}</p>
                        <p>版本: {{this.info.version}}</p>
                        <p v-if="this.info.protocol !== null">协议版本: {{this.info.protocol}}</p>
                        <p>类型: {{ (this.info.beta)? "测试" : "正式版"}}</p>
                        <p>下载地址: <a :href="this.info.download" target="_blank">{{this.info.download}}</a></p>
                        <p>文件大小: {{this.info.size}}</p>
                        <p>更新时间: {{this.info.time}}</p>
                        <span>原始数据: <pre>{{this.info}}</pre></span>
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
        loading: false,
        only_download: false,
        info: null,
        cache_data: null,
        select_version: null,
        marjor_version: null,
        marjor_versions: null
    }),
    created() {
        this.pull_data(true);
    },
    watch:{
        type(newVal,oldVal){
            this.pull_data();
            this.get_info(this.select_version)

        },
        only_download(newVal,oldVal){
            this.get_info(newVal)
            this.pull_data();
        },
        select_version(newVal,oldVal){
            this.loading = true;
            this.get_info(newVal)
        },
        marjor_version(newVal,oldVal){
            this.loading = true;
            this.change_marjor(newVal);
        }
    },
    methods:{
        pull_data(first = false){
            this.get_cache(first);
            this.loading = true;
            /*  this.axios.get("/api").then((res)=>{
                  if(res.data.success){

                  }else{
                      this.get_cache();
                  }
              }).catch((err) => {
                  this.get_cache();
              });*/
        },
        change_marjor(version){
            this.get_cache(false,version)
        },
        get_cache(first = false){
            this.axios.get("/data.json").then((res) => {
                const data = res.data;
                this.cache_data = data;
                var versions_data = [];
                var marjor_versions_data = [
                    '显示全部'
                ];
                var marjor_data = [];
                if(this.marjor_version !== null && this.marjor_version !== "显示全部"){
                    data.forEach((item) => {
                        if(item.marjor == this.marjor_version){
                            marjor_data.push(item);
                        }
                    })
                }else{
                    marjor_data = data;
                }
                marjor_data.forEach((item) => {
                    marjor_versions_data.push(item.marjor);

                    if(this.type === "all"){
                        if(this.only_download){
                            if(item.download == null){

                            }else{
                                if(item.beta){
                                    versions_data.push(item.version+" [测试版]");
                                }else{
                                    versions_data.push(item.version);
                                }
                            }
                        }else{
                            if(item.beta){
                                versions_data.push(item.version+" [测试版]");
                            }else{
                                versions_data.push(item.version);
                            }
                        }

                    }else if(this.type === "stable"){
                        if(this.only_download) {
                            if (item.download == null) {

                            } else {
                                if (!item.beta) {
                                    versions_data.push(item.version);
                                }
                            }
                        }else{
                            if (!item.beta) {
                                versions_data.push(item.version);
                            }
                        }
                    }else if(this.type === "beta"){
                        if(this.only_download) {
                            if (item.download == null) {

                            } else {
                                if (item.beta) {
                                    versions_data.push(item.version);
                                }
                            }
                        }else{
                            if (item.beta) {
                                versions_data.push(item.version);
                            }
                        }
                    }else if(this.type === "pe"){
                        if(this.only_download) {
                            if (item.download == null) {

                            } else {
                                if (item.pe) {
                                    versions_data.push(item.version);
                                }
                            }
                        }else{
                            if (item.pe) {
                                versions_data.push(item.version);
                            }
                        }
                    }
                });

                this.versions = versions_data;
                this.loading = false;
                if(first){
                    this.marjor_versions = [...new Set(marjor_versions_data)];
                    this.get_info(versions_data[0]);
                }
            })
        },
        get_info(version){
            this.cache_data.forEach((item) => {
                if(item.version === version){
                    this.info = item;
                    this.loading = false

                }
            });
        }
    }

}
</script>
