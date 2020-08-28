<template>
  <div class="detail">
    <div class="inner">
        <div class="timestamp">
            <div 
                class="link"
                @click="goToRanking()"
            >＜ ランキングへ</div>
            <div class="text">発生確認日：{{rumorDetail.created_at}}</div>
        </div>
        <div class="mt10" />
        <div class="content">
            {{rumorDetail.content}}
        </div>
        <div class="mt30" />
        <div class="fix_and_updown">
            <div class="fix">
                <div class="title">訂正数</div>
                <div class="flex_row">
                    <div class="num">{{rumorDetail.fix}}</div>
                    <div class="scale">件</div>
                </div>
            </div>
            <div class="ml50" />
            <div class="updown">
                <div class="title">昨日と比べて</div>
                <div class="flex_row">
                    <div class="num" :style="{color: returnUpDownColor(rumorDetail.updown)}">{{returnUpDown(rumorDetail.updown)}}</div>
                    <div class="scale">件</div>
                </div>
            </div>
        </div>
        <div class="mt40" />
        <div class="fix_tweets">
            <div class="title">訂正ツイート</div>
            <div class="mt10" />
            <div class="text">{{rumorDetail.fix_tweets}}</div>
        </div>
        <div class="mb20" />
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Detail',
  props: {
      rumorIdFromRank: ''
  },
  data () {
    return {
        rumorId: '',
        rumorDetail: JSON,
    }
  },
  created () {
    this.liffInit()
    liff.ready.then(() => {
        // do something you want when liff.init finishes
        this.getRumorId()
        this.getRumorDetail()
        this.submitCheckLog()
    })
  },
  methods: {
    liffInit () {
        liff.init({liffId: "1654776413-dpYy83Wb"})
    },
    getRumorId () {
        const params = new URLSearchParams(decodeURIComponent(window.location.search.substring(1)))
        this.rumorId = params.get('id') // /?key=hogeでしていしたhogeを取得できる
        if(this.rumorIdFromRank) {
            this.rumorId = this.rumorIdFromRank
        }
        console.log(this.rumorId)
    }, 
    submitCheckLog () {
        liff.getProfile()
        .then((response) => {
            if(response.userId) {
                const userId = response.userId //userId,displayName,pictureUrl,statusMessage
                const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/writeUserLog.php"
                let params = new URLSearchParams()
                params.append("userId", userId)
                axios.post(url, params)
            }
        })
        .catch(error => {
            console.log(error)
        })
    },
    async getRumorDetail () {
        const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getRumorDetail.php"
        let params = new URLSearchParams();
        params.append("rumorId", this.rumorId)
        const response = await axios.post(url, params)
        console.log(response.data)
        this.rumorDetail = response.data[0]
    },
    returnUpDown (updownNum) {
        if (updownNum < 0) {
            return updownNum
        } else if (updownNum == 0) {
            return "±" + updownNum
        } else if (updownNum > 0) {
            return "+" + updownNum
        }
    },
    returnUpDownColor (updownNum) {
        if (updownNum < 0) {
            return "#E95513"
        } else if (updownNum == 0) {
            return "#4B4B4B"
        } else if (updownNum > 0) {
            return "#4483BC"
        }
    },
    goToRanking () {
        this.$emit("setPath", "ranking")
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.detail {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #4B4B4B;
}

.inner {
    height: calc(100% - 40px);
    width: calc(100% - 40px);
}

.text, .scale, .link {
    font-size: 14px;
}

.link {
    color: #3498CB;
}

.timestamp {
    display: flex;
    justify-content: space-between;
}

.content {
    background-color: #07B53B;
    color: #FFF;
    padding: 30px 10px;
    border-radius: 3px;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
}

.fix_and_updown {
    display: flex;
    flex-direction: row;
    align-items: top;
    justify-content: center;
}

.title {
    font-size: 18px;
    font-weight: bold;
    display: flex;
}

.num {
    font-size: 28px;
    font-weight: bold;
}

.flex_row {
    display: flex;
    flex-direction: row;
    align-items: center;
}

.scale {
    margin: 10px 0 0 5px;
}

</style>
