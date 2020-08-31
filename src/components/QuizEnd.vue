<template>
  <div class="quizEnd">
    <div class="header">
        流言クイズ
    </div>
    <div class="mt30" />
    <div class="mt30" />
    <div class="mt10" />
    <div class="subComponent">
        <div>今日はここまで！</div>
        <div>また明日挑戦してね！</div>
    </div>
    <div class="mt30" />
    <div class="subComponent">
        <div class="nums">
            <div class="subtitle">今日の回答数</div>
            <div class="mt10" />
            <span class="num">{{userProfile.answer_today}}/10<span class="subtitle">件</span></span>
        </div>
        <div class="nums">
            <div class="subtitle">今日の正答率</div>
            <div class="mt10" />
            <span class="num">{{getCorrectPer(userProfile.answer_today, userProfile.answer_correct_today)}}<span class="subtitle">%</span></span>
        </div>
    </div>
    <div class="mt20" />
    <div class="border" />
    <div class="mt20" />
    <div class="subComponent">
        <div class="nums">
            <div class="subtitle">すべての回答数</div>
            <div class="mt10" />
            <span class="num">{{userProfile.answer_sum}}<span class="subtitle">件</span></span>
        </div>
        <div class="nums">
            <div class="subtitle">すべての正答率</div>
            <div class="mt10" />
            <span class="num">{{getCorrectPer(userProfile.answer_sum, userProfile.answer_correct)}}<span class="subtitle">%</span></span>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'quizEnd',
  data () {
    return {
        userProfile: '',
        userId: '',
    }
  },
  created () {
    this.liffInit()
    liff.ready.then(() => {
        // do something you want when liff.init finishes
        if(!this.checkLogIn()) {
            liff.login();
        }
        this.getUserProfile()
    })
  },
  methods: {
    liffInit () {
        liff.init({liffId: "1654776413-dpYy83Wb"})
    },
    checkLogIn  () {
        return liff.isLoggedIn()
    },
    getUserProfile () {
        liff.getProfile()
        .then((response) => {
            this.userId= response.userId
            const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getUserLog.php"
            let params = new URLSearchParams();
            params.append("line_user_id", this.userId)
            axios.post(url, params)
            .then((response)=>{
                this.userProfile = response.data[0]
            })
        })
        .catch(error => {
            console.log(error)
        })
    },
    getCorrectPer (sum, correct) {
        if(sum == 0) {
            return 0
        }
        const per = (correct / sum) * 100
        return parseInt(per, 10);
    },
    correctMessage () {
        const per = this.getCorrectPer(this.userProfile.answer_today, this.userProfile.answer_correct_today)
        if(per === 100) {
            return "パーフェクト！君は流言マスターだね。"
        } else if(per > 80) {
            return "すごい正解率！その調子！"
        } else if(per > 60) {
            return "なかなかの正解率！"
        } else if(per > 30) {
            return "怪しい情報に騙されちゃだめだよ...！"
        } else {
            return "もしかして騙されやすい...？"
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

.quizEnd {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #4B4B4B;
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

.content, .contentFill {
    background-color: #FFF;
    border: solid 3px #07B53B;
    color: #4b4b4b;
    padding: 30px 10px;
    border-radius: 3px;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
}

.contentFill {
    background-color: #07B53B;
    color: #FFF;
}

.component {
    display: flex;
    justify-content: center;
    align-items: center;
}

.ghost {
    width: 30px;
}

.message {
    font-size: 14px;
    font-weight: bold;
}

.grid {
    display: grid;
    grid-template-columns: 49% 49%;
    grid-template-rows: 50px 50px;
    grid-gap: 10px 5px;
    
    align-items: center;
    text-align: center;
}

.buttonText {
    font-size: 24px;
    padding: 10px;
    font-weight: bold;
    background-color: #07B53B;
    color: #FFF;
    border-radius: 3px;
}

.subComponent {
    display: flex;
    align-items: center;
    justify-content: center;
}

.title {
    font-size: 14px;
    font-weight: bold;
}

.nums {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 20px;
}

.subtitle {
    font-size: 12px;
}

.num {
    font-size: 24px;
    font-weight: bold;
}

.nextButton {
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    color: #3498CB;
}
</style>
