<template>
  <div class="quiz">
    <div class="inner">
        <div v-if="!onAnswer">
            <p>{{hideKeyword()}}</p>
            <p>〇〇に入る言葉は何かな？</p>
            <div 
                v-for="h in hints" :key="h"
                @click="submitAnswer(h)"
            >{{h}}</div>
        </div>
        <div v-else>
            {{test}}
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
        max: 0,
        onAnswer: false, // 回答は隠す
        isCorrect: false,
        userProfile: '',
        test: ''
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
    getAnswerData (isCorrect) {
        liff.getProfile()
        .then((response) => {
            // this.userProfile = response //userId,displayName,pictureUrl,statusMessage
            const userId = response.userId
            const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getAnswerData.php"
            let params = new URLSearchParams();
            params.append("userId", userId)
            params.append("isCorrect", isCorrect)
            axios.post(url, params).then((res)=>{
                this.test = res.data
                return res.data
            })
        })
        .catch(error => {
            console.log(error)
        })
    },
    async init () {
        const rumors = await this.getRumors()
        this.quizRumors = this.getRumorsForQuiz(rumors)
        this.max = this.quizRumors.length
        this.setQuizRumor()
        this.setHints()
        // console.log(this.quizRumors)
        // console.log(this.max)
    },
    async getRumors () {
        const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getRumors.php"
        const response = await axios.post(url)
        return response.data
    },
    getRumorsForQuiz (rumors) {
        let quizRumors = []
        for(let i=0; i<rumors.length; i++) {
            let morphemes = rumors[i].morpheme.split('/');
            if(morphemes[0].length == 2) {
                quizRumors.push(rumors[i])
            }
        }
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
        for(let i=0; i<hintRumors.length; i++) {
            hints.push(hintRumors[i].morpheme.split('/')[0])
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
        this.getAnswerData(this.isCorrect)
        this.onAnswer = true
    },
    judgeAnswer (answer) {
        if(this.nowKeyword === answer) {
            return true
        } else {
            return false
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
    color: #4B4B4B;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.inner {
    height: calc(100% - 40px);
    width: calc(100% - 40px);
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>
