<template>
  <div class="ranking">
    <div class="header">
        流言ランキング
    </div>
    <div class="mt50" />
    <div class="mt50" />
    <div class="inner">
        <div
            class="component" 
            v-for="(r, i) in rumors" :key="r.id"
        >
            <div class="row">
                <div class="rank">{{i + 1}}</div>
                <div class="ml20" />
                <div class="colmn">
                    <div 
                        @click="goDetailPage(r.id)"
                        class="content"
                    >{{r.content}}</div>
                    <div class="mt5" />
                    <div class="fixInfo">
                        <span class="green">{{r.fix}}</span>人が訂正（昨日より<span :style="{color: updownColor(r.updown)}">{{addMark(r.updown)}}</span>人）
                    </div>
                </div>
            </div>
            <div class="mt20" />
            <div class="border" />
            <div class="mt20" />
        </div>
    </div>
</div>

</template>

<script>
import axios from 'axios'

export default {
  name: 'ranking',
  data () {
    return {
        rumors: ''
    }
  },
  created () {
    this.liffInit()
    liff.ready.then(() => {
        // do something you want when liff.init finishes
        this.init()
    })
  },
  methods: {
    liffInit () {
        liff.init({liffId: "1654776413-dpYy83Wb"})
    },
    async init () {
        const rumors = await this.getRumors()
        this.rumors = rumors
    },
    async getRumors () {
        const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getRumors.php"
        const response = await axios.post(url)
        return response.data
    },
    addMark (num) {
        if(num > 0) {
            return "+" + num
        } else if (num == 0) {
            return "±" + num
        } else {
            return num
        }
    },
    goDetailPage (id) {
        console.log("発火")
        this.$emit("setId", id)
        this.$emit("setPath", "detail")
    },
    updownColor(num) {
        if(num > 0) {
            return "#07B53B"
        } else if (num == 0) {
            return "#4B4B4B"
        } else {
            return "#FF5205"
        }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
p {
    margin: 0;
    padding: 0;
}

.ranking {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #4B4B4B;
    overflow: scroll;
}

.header {
    position: fixed;
    top: 0;
    width: 100%;
    height: 40px;
    font-weight: bold;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #07B53B;
    color: #FFF;
    border-bottom: solid 1px #FFF;
}

.inner {
    height: calc(100% - 40px);
    width: calc(100% - 40px);
}

.row {
    display: flex;
    align-items: flex-start;
}

.rank {
    min-width: 30px;
    font-size: 18px;
    font-weight: bold;
}

.colmn {
    display: flex;
    flex-direction: column;
}

.content {
    margin-top: -3px;
    font-size: 20px;
    font-weight: bold;
}

.fixInfo {
    font-size: 14px;
}
</style>
