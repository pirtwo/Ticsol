<template>
    <div class="wrap-autocomplete">
        <input 
          v-model="input" 
          v-on:input="debounceOnInput" 
          v-on:change="debounceOnChange"
          v-on:focus="showList = true" 
          v-on:blur="debounceOnBlur"         
          class="" 
          type="text" 
          name="" 
          placeholder=""/>
        <ul class="autocomplete-results" v-show="showList">
            <li class="autocomplete-result" 
                v-for="(item, index) in results" :key="index"
                v-on:click="onSelect(item, index)">
                {{ item }}
            </li>
        </ul>
    </div>
</template>

<script>
import _ from "lodash";
export default {
  name: "BaseAutoComplete",
  props: {
    data: {
      type: Array,
      required: true,
      default: []
    },
    name: {
      type: String,
      required: true,
      default: ""
    },
    value: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      input: "",
      results: this.data,
      selectedIndex: -1,
      showList: false
    };
  },
  created() {
    this.debounceOnBlur = _.debounce(this.onBlur, 200);
    this.debounceOnInput = _.debounce(this.onInput, 1000);
    this.debounceOnChange = _.debounce(this.onChange, 1000);    
  },
  methods: {
    onInput() {      
      this.showList = true;
      this.results = this.data.filter(
        item => item.toLowerCase().indexOf(this.input.toLowerCase()) > -1
      );      
    },

    onChange(event){
      this.$emit("input", event.target.value);      
    },

    onSelect(value, index) {
      this.input = value;
      this.selectedIndex = index;
      this.$emit("input", value);
      this.showList = false;
    },

    onBlur() {
      this.showList = false;
    }
  }
};
</script>

<style scoped>
input[type="text"] {
  width: 100%;
}

ul {  
  margin: 0px;
  padding: 5px;
  max-height: 100px;
  cursor: pointer;
  list-style: none;
  overflow-y: scroll;
  overflow-x: hidden;  
  background-color: white;
}

ul li:hover {
  background-color: rgba(0, 0, 0, 0.05);
}
</style>
