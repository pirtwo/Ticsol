<template>
  <div class="ts-section">
    <div class="ts-section__title">    
      {{ title }}
    </div>
    <div
      v-if="devider"
      class="ts-section__devider"
    />
    <div class="ts-section__content">
      <slot />
    </div>    
  </div>
</template>

<script>
export default {
  name: "TsSection",

  props: {
    title: {
      type: String,
      default: ""
    },
    devider:{
      type:Boolean,
      default: true
    }
  }
};
</script>

<style scoped>
.ts-section {
  padding: 20px;
  border: 1px solid rgba(0, 0, 0, 0.1);
  background-color: rgba(255, 255, 255, 0.1);
  
}

.ts-section__title{
  margin-bottom: 5px;
  font-weight: bold;
}

.ts-section__devider{
  border-bottom: 1px solid;
  opacity: 0.2;
}

.ts-section__content{
  margin-top: 20px;
}
</style>