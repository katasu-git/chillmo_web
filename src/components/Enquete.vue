<template>
  <div class="enquete">
        <div class="header" id="top">アンケート</div>
        <div class="inner">
            
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
                <div class="bold">Q.{{counter + 1}}</div>
                <div class="bold ml20">{{questionJSON[counter].mainText}}</div>
            </div>

            <div class="mt20" />
            <img class="mainImage" :src="questionJSON[counter].image_path" />

            <div class="mt20" />
            <form
                v-for="(s, i) in questionJSON[counter].selecters" 
                :key="i"
            >
              <div class="span">
                <input :type="questionJSON[counter].type" :value="s" v-model="selectData"/>
                <div class="ml10" />
                <label :for="s">{{s}}</label>
              </div>
            </form>

            <div class="mt20" />
            <div class="bold">理由：</div>
            <textarea class="input_text" wrap="soft" v-model="reason"></textarea>

            <div class="mt30" />
            <button @click="setCounter()" v-scroll-to="'#top'">次の質問へ</button>
            <div>
                {{selectData}}
                {{reason}}
            </div>
        </div>
  </div>
</template>

<script>
// import Question from '@/components/Question'

export default {
  name: 'enquete',
  data () {
    return {
        counter: 0,
        questionJSON: [
            {
                "mainText": "システムの利⽤期間，⽇ごろ⽬にする情報の真偽を確認することが増えた",
                "selecters" : ["強く同意する", "同意する", "どちらでもない", "同意しない", "強く同意しない"],
                "type": "radio",
                "model": "selectData",
                "image_path": require("../assets/image.jpeg")
            },
            {
                "mainText": "システムを継続して使う助けになる機能",
                "selecters" : ["強く同意する", "同意する", "どちらでもない", "同意しない", "強く同意しない"],
                "type": "checkbox",
                "model": "checkData",
                "image_path": require("../assets/image.jpeg")
            }
        ],
        selectData: [],
        reason: '',
        error: false
    }
  },
  created () { 
    this.liffInit()
        liff.ready.then(() => {
            // do something you want when liff.init finishes
            if(!this.checkLogIn()) {
                // liff.login();
            }
        })
  },
  methods: {
    liffInit () {
        liff.init({liffId: "1654776413-dpYy83Wb"})
    },
    checkLogIn  () {
        return liff.isLoggedIn()
    },
    setCounter () {
        console.log(this.selectData)
        if(this.selectData.length == 0 || !this.reason) {
            this.error = true
            return
        }
        if(this.counter + 2 > this.questionJSON.length) {
            // 終了処理
            this.counter = 0
        } else {
            this.counter++
        }
        // 初期化
        this.selectData = []
        this.reason = ''
        this.error = false
    },
    progressWidth () {
        const par = this.counter / this.questionJSON.length * 100
        return par + "%"
    },
    qLeft () {
        return this.questionJSON.length - this.counter
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
    font-size: 14px;
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
    margin-top: -2px;
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
}

.input_text {
    width: calc(100% - 2px);
    height: 60px;
    border: solid rgba(0,0,0,.12) 1px;
    border-radius: 3px;
}

button {
    width: 100%;
    border: none;
    background: none;
    font-weight: bold;
    font-size: 18px;
    color: #3498CB;
}

.progress {
    height: 20px;
    position: relative;
}

.indexTop {
    position: absolute;
    width: 100%;
    text-align: center;
    font-weight: bold;
    color: #FFF;
}

.bar-background, .bar-top {
    position: absolute;
    width: 100%;
    height: 20px;
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
    font-size: 16px;
    
}

</style>
