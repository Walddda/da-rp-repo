<template>
  <div class="fixed bottom-0 right-0 bg-green-500 " :style="{'opacity': opac+'%', 'transition': 'all 500ms linear'}">
      <div class="bg-green-200 h-2"
      :style="{'width': length+'%', 'transition': 'all 1s linear'}"
      ></div>
      <div class="py-4 px-8 text-white font-medium">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
<i class="fa fa-regular fa-thumbs-up"></i>
      {{text}}</div>
      </div>
</template>

<script>
export default {
    props: {
        text: String,
    },
    data(){
        return {
            length: 0,
            int: null,
            opac: 0,
        }
    },
    created(){
        // 
    },
    unmounted(){
        // 
        clearInterval(this.int)
        },
    mounted(){
        // 
        console.info({success: this.text})
        this.length = 110;
        this.opac = 0;
        // this.$nextTick(function(){
        //     this.dec()
        // });
        this.dec()
        this.int = setInterval(this.dec, 500)
    },
    methods:{
        dec(){
            if(this.length <= 10){
                this.opac = 0;
            }
            else if(this.length <= 100){
                this.opac = 100;
            }
            if(this.length <= 0){
                clearInterval(this.int)
                this.length = 110;
                this.opac = 0;
                this.$emit('close');
            }else{
                this.length -= 10;
            }
        },
    }
}
</script>