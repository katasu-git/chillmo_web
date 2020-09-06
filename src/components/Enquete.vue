<template>
  <div class="enquete">
        <div class="header" id="top">アンケート</div>
        <div v-if="!qEnd" class="inner">
            
            <div class="mt20" />
            <div class="progress">
                <div class="bar-background" />
                <div class="bar-top" :style="{width: progressWidth()}"/>
                <div class="indexTop">あと{{qLeft()}}問</div>
            </div>

            <div v-show="error">
                <div class="mt20" />
                <div class="error">すべての項目を入力してください</div>
            </div>

            <div class="mt20" />
            <div class="span">
                <div class="bold">Q{{counter + 1}}.</div>
                <div class="bold ml20">{{questionJSON[counter].mainText}}</div>
            </div>

            <div class="mt20" />
            <img 
                v-show="questionJSON[counter].image_path" 
                class="mainImage" 
                :src="questionJSON[counter].image_path" 
            />

            <div class="mt20" />
            <form
                v-for="(s, i) in questionJSON[counter].selecters" 
                :key="i"
                v-show="questionJSON[counter].type"
            >
                <div class="mt5" />
                <div class="span">
                    <input :type="questionJSON[counter].type" :value="s" v-model="selectData"/>
                    <div class="ml10" />
                    <label :for="s">{{s}}</label>
                </div>
            </form>

            <div class="mt20" />
            <div v-show="questionJSON[counter].reason == 'true'">
                <div 
                    class="bold" 
                    v-show="questionJSON[counter].type"
                >理由：</div>
                <textarea class="input_text" wrap="soft" v-model="reason"></textarea>
            </div>

            <div class="mt30" />
            <button @click="setCounter()" v-scroll-to="'#top'">次の質問へ</button>
        </div>
        <div class="end_message" v-else>
            <div class="mt40" />
            <img src="../assets/ghost.svg" class="ghost" />
            <div class="mt20" />
            <div>アンケートは以上です</div>
            <div>ご協力ありがとうございました！</div>
        </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'enquete',
  data () {
    return {
        userId: '',
        counter: 0,
        questionJSON: [
            {
                "mainText": "",
                "selecters" : [],
                "type": "",
                "model": "",
                "image_path": "",
                "reason": "true"
            }
        ],
        selectData: [],
        reason: '',
        error: false,
        qEnd: false
    }
  },
  created () { 
    this.liffInit()
        liff.ready.then(() => {
            // do something you want when liff.init finishes
            if(!this.checkLogIn()) {
                liff.login();
            }
            this.getUserId()
        })
  },
  methods: {
    liffInit () {
        liff.init({liffId: "1654776413-dpYy83Wb"})
    },
    checkLogIn  () {
        return liff.isLoggedIn()
    },
    getUserId () {
        liff.getProfile()
        .then((response) => {
            this.userId= response.userId
            const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getUserLog.php"
            let params = new URLSearchParams();
            params.append("line_user_id", this.userId)
            axios.post(url, params)
            .then(res=>{
                console.log(res)
                if(res.data[0].test_group == 0) {
                    axios.get("https://www2.yoslab.net/~nishimura/chillmoWeb/static/question_g0.json").then(res=>{
                        this.questionJSON = res.data
                        console.log(res.data)
                    })

                } else if(res.data[0].test_group == 1 || res.data[0].test_group == 2) {
                    axios.get("https://www2.yoslab.net/~nishimura/chillmoWeb/static/question_g1.json").then(res=>{
                        this.questionJSON = res.data
                        console.log(res.data)
                    })
                }
            })
        })
        .catch(error => {
            console.log(error)
        })
    },
    setCounter () {
        if((this.questionJSON[this.counter].type && this.selectData.length == 0) 
            || (this.questionJSON[this.counter].reason == "true" && !this.reason)) {

            this.error = true
            return
            
        }
        this.submitQAnswerDB()
        // 初期化
        this.selectData = []
        this.reason = ''
        this.error = false
        if(this.counter + 2 > this.questionJSON.length) {
            // 終了処理
            this.qEnd = true
        } else {
            this.counter++
        }
    },
    progressWidth () {
        const par = this.counter / this.questionJSON.length * 100
        return par + "%"
    },
    qLeft () {
        return this.questionJSON.length - this.counter
    },
    async submitQAnswerDB () {
        const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/submitQAnswerDB.php"
        let params = new URLSearchParams();
        let dataStr = ""
        if(this.selectData) {
            if(this.questionJSON[this.counter].type == "radio") {
                dataStr = this.selectData
            } else {
                for(let i=0; i<this.selectData.length; i++) {
                    dataStr += this.selectData[i]
                    if(i < this.selectData.length - 1) {
                        dataStr += "/"
                    }
                }
            }
            params.append("dataStr", dataStr)
        }
        if(this.reason != '') {
            params.append("reason", this.reason)
        }
        params.append("userId", this.userId)
        params.append("Qnum", (this.counter + 1))
        const res = await axios.post(url, params)
        console.log(res)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.enquete {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #4B4B4B;
    font-size: 18px;
    overflow: scroll;
}

.header {
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
    width: calc(100% - 40px);
}

.span {
    position: static;
    display: flex;
    justify-content: flex-start;
}

.mainText {
    margin-top: -5px;
}

.mainImage {
    width: 100%;
}

.bold {
    font-weight: bold;
}

input, textarea {
    margin: 0;
    padding: 0;
    font-size: 18px;
}

.input_text {
    width: calc(100% - 2px);
    height: 60px;
    border: solid rgba(0,0,0,.12) 1px;
    border-radius: 3px;
    font-size: 18px;
}

button {
    width: 100%;
    border: none;
    background: none;
    font-weight: bold;
    color: #3498CB;
    font-size: 18px;
}

.progress {
    height: 40px;
    position: relative;
}

.indexTop {
    position: absolute;
    height: 40px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    color: #FFF;
}

.bar-background, .bar-top {
    position: absolute;
    width: 100%;
    height: 40px;
    border-radius: 10px;
    background-color: rgba(0,0,0,.12);
}

.bar-top {
    background-color: #3498CB;
    transition-duration: 1s;
}

.error {
    width: 100%;
    font-weight: bold;
    text-align: center;
    color: #EF7943;
    
}

.end_message {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.ghost {
    width: 100px;
}

</style>
