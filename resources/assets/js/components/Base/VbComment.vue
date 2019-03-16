<template>
  <div 
    :id="id"
    class="vb-comment d-flex"
  >    
    <img 
      class="vb-comment__avatar rounded border"
      :src="avatar" 
      :alt="`${username} avatar`"
    >   
    <div class="vb-comment__body d-flex flex-column flex-grow-1">
      <div class="d-flex align-items-baseline">
        <div class="vb-comment__name">
          {{ username }}
        </div>
        <div class="vb-comment__date">
          {{ date }}
        </div>
        <button 
          v-if="hasReply"
          type="button" 
          class="btn btn-sm btn-link ml-auto"
          @click="onReply"
        >
          reply
        </button>
        <button 
          v-if="hasReply"
          type="button" 
          class="btn btn-sm btn-link"
          @click="onReply"
        >
          edit
        </button>
        <button 
          v-if="hasReply"
          type="button" 
          class="btn btn-sm btn-link"
          @click="onReply"
        >
          delete
        </button>
      </div>      
      <div class="">
        {{ body }}  
      </div>       
    </div>    
  </div>
</template>

<script>
export default {
  name: "VbComment",

  props: [
    'id', 
    'username', 
    'avatar', 
    'body', 
    'date', 
    'hasReply', 
    'hasEdit', 
    'hasDelete'],

  data() {
    return {};
  },

  created() {},

  mounted() {},

  methods: {
    onReply(e){
      e.preventDefault();
      if(!this.hasReply) return;
      this.$emit('reply', this.id);
    }
  }
};
</script>

<style scoped>
.vb-comment{
  margin-bottom: 10px;
}

.vb-comment__name{
  font-size: 12px;
  font-weight: bold;
}

.vb-comment__avatar{
  width: 55px;
  height: 55px;
  margin: 20px;
}

.vb-comment__date{
  font-size: 10px;
  margin-left: 7px;
}

.vb-comment__body{
  border: 1px solid #0000001a;
  background-color: #ffffffe6;
  border-radius: 5px;
  padding: 10px;
}
</style>