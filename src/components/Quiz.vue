<template>
  <div class="quiz">
    <div class="header">
        流言クイズ
    </div>
    <div class="mt30" />
    <div class="mt30" />
    <div class="mt10" />
    <div class="inner">
        <div v-show="!onAnswer">
            <div class="content">{{hideKeyword()}}</div>
            <div class="mt20" />
            <div class="component">
                <img src="../assets/ghost.svg" class="ghost" />
                <div class="ml20" />
                <div class="message"> 〇〇に入る言葉は何かな？</div>
            </div>
            <div class="mt30" />
            <div class="grid">
                <div 
                    class="buttonText"
                    v-for="(h,i) in hints"
                    :key="i"
                    @click="submitAnswer(h)"
                >{{h}}</div>
            </div>
        </div>
        <div v-show="onAnswer">
            <div class="contentFill">{{nowRumor.content}}</div>
            <div class="mt20" />
            <div class="component">
                <img src="../assets/ghost.svg" class="ghost" />
                <div class="ml20" />
                <div class="message">
                    <div v-if="isCorrect">
                        <p>おみごと！<span class="green">正解！</span></p>
                        <p>{{correctMessage()}}</p>
                    </div>
                    <div v-else>残念！<span class="red">不正解...</span></div>
                </div>
            </div>
            <div class="mt20" />
            <div class="border" />
            <div class="mt20" />
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
            <div
                class="nextButton" 
                @click="nextQuiz()"
            >
                次のクイズに挑戦
            </div>
            <div class="mt40" />
            <div class="fix_tweets">
                <div class="title">訂正ツイート</div>
                <div class="mt10" />
                <div class="text">{{nowRumor.fix_tweets}}</div>
            </div>
            <div class="mb20" />
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'quiz',
  data () {
    return {
        quizRumors: '',
        nowRumor: '',
        nowKeyword: '',
        hints: [],
        counter: 0,
        onAnswer: false, // 回答は隠す
        isCorrect: false,
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
        this.init()
    })
  },
  methods: {
    liffInit () {
        liff.init({liffId: "1654776413-dpYy83Wb"})
    },
    checkLogIn  () {
        return liff.isLoggedIn()
    },
    async init () {
        this.getUserProfile()
        const rumors = await this.getRumors()
        this.quizRumors = this.getRumorsForQuiz(rumors)
        this.nextQuiz()
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
                this.checkEnd()
            })
        })
        .catch(error => {
            console.log(error)
        })
    },
    getAnswerData () {
            const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getAnswerData.php"
            let params = new URLSearchParams();
            params.append("userId", this.userId)
            params.append("rumorId", this.nowRumor.id)
            params.append("isCorrect", this.isCorrect)
            axios.post(url, params).then((res)=>{
                this.userProfile = res.data
                this.checkEnd()
                this.onAnswer = true
            })
    },
    checkEnd () {
        if(this.userProfile.answer_today > 9 && this.getNowYMD() == this.userProfile.last_answer_date) {
            this.$emit('setPath', 'quizEnd')
        }
    },
    getNowYMD(){
        var dt = new Date();
        var y = dt.getFullYear();
        var m = ("00" + (dt.getMonth()+1)).slice(-2);
        var d = ("00" + dt.getDate()).slice(-2);
        var result = y + "-" + m + "-" + d;
        return result;
    },
    async getRumors () {
        const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getRumors.php"
        const response = await axios.post(url)
        return response.data
    },
    getRumorsForQuiz (rumors) {
        let quizRumors = []
        for(let i=0; i<rumors.length; i++) {
            if(rumors[i].morpheme) {
                let morphemes = rumors[i].morpheme.split('/');
                if(morphemes[0].length == 2) {
                    quizRumors.push(rumors[i])
                }
            }
        }
        quizRumors = this.shuffle(quizRumors)
        return quizRumors
    },
    setQuizRumor () {
        this.nowRumor = this.quizRumors[this.counter]
        this.nowKeyword = this.getKeyword()
    },
    getKeyword () {
        const keyword = this.nowRumor.morpheme.split('/')[0]
        return keyword
    },
    hideKeyword () {
        if(this.nowRumor) {
            const hideText = this.nowRumor.content.replace(this.nowKeyword, "〇〇")
            return hideText
        } else {
            return ''
        }
        
    },
    setHints () {
        const hintRumors = this.arrayRandom(this.quizRumors, 3)
        let hints = [this.nowKeyword]
        for(let j=0;;j++) {
            for(let i=0; i<hintRumors.length; i++) {
                hints.push(hintRumors[i].morpheme.split('/')[0])
            }
            hints = hints.filter((x, k, self) => self.indexOf(x) === k); // 重複を削除
            if(hints.length > 3) {
                break
            }
        }

        hints = this.shuffle(hints)
        this.hints = hints
        
    },
    arrayRandom (arr, count) {
        if (!count) count = 1
        var data = [], i, num;
        for (i = 0; i < count; i++) {
            num = Math.floor(Math.random() * arr.length)
            data.push(arr.splice(num, 1)[0])
        }
        return data
    },
    shuffle ([...arr]) {
        let m = arr.length;
        while (m) {
            const i = Math.floor(Math.random() * m--);
            [arr[m], arr[i]] = [arr[i], arr[m]];
        }
        return arr;
    },
    submitAnswer (answer) {
        this.isCorrect = this.judgeAnswer(answer)
        this.getAnswerData()
        
    },
    judgeAnswer (answer) {
        if(this.nowKeyword === answer) {
            return true
        } else {
            return false
        }
    },
    getCorrectPer (sum, correct) {
        if(sum == 0) {
            return 0
        }
        const per = (correct / sum) * 100
        return parseInt(per, 10);
    },
    nextQuiz () {
        this.counter++
        this.setQuizRumor()
        this.setHints()
        this.onAnswer = false
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

.quiz {
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
