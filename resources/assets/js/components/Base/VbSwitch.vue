<template>
  <label
    ref="vbSwitch"
    :class="[size, style, 'vb-switch']"
  >
    <input
      ref="input"
      type="checkbox"
      :id="id"
      :name="name"
      :value="val"
      :checked="isChecked"
      @blur="$emit('blur', $event)"
      @change="$emit('change', $event)"
      @click="$emit('click', $event)"
      @dblclick="$emit('dblclick', $event)"
      @focus="$emit('focus', $event)"
      @mousedown="$emit('mousedown', $event)"
      @mousemove="$emit('mousemove', $event)"
      @mouseout="$emit('mouseout', $event)"
      @mouseover="$emit('mouseover', $event)"
      @mouseup="$emit('mouseup', $event)"
      @input="onInput"
    >
    <span class="vb-switch__slider" />
  </label>
</template>

<script>
export default {
  name: 'VbSwitch',

  props: {
    id: {
      type: String,
      default: ''
    },
    name: {
      type: String,
      default: ''
    },
    value: {
      type: [Boolean, Array],
      default: false
    },
    switchStyle: {
      type: String,
      default: 'normal',
      validator: function (value) {
        return ['round', 'normal', 'sharp'].indexOf(value) !== -1
      }
    },
    switchSize: {
      type: String,
      default: 'md',
      validator: function (value) {
        return ['sm', 'md', 'lg'].indexOf(value) !== -1
      }
    }
  },

  data () {
    return {
      val: ''
    }
  },

  computed: {
    isChecked: function () {
      if (Array.isArray(this.value)) {
        return this.value.indexOf(this.val) > -1
      } else {
        return this.value
      }
    },

    size: function () {
      if (this.switchSize === 'sm') return 'vb-switch--sm'
      else if (this.switchSize === 'md') return 'vb-switch--md'
      else if (this.switchSize === 'lg') return 'vb-switch--lg'
    },

    style: function () {
      if (this.switchStyle === 'normal') return 'vb-switch--normal'
      else if (this.switchStyle === 'round') return 'vb-switch--round'
      else if (this.switchStyle === 'sharp') return 'vb-switch--sharp'
    }
  },

  mounted () {
    this.val = this.$refs.vbSwitch.getAttribute('value')
  },

  methods: {
    onInput (event) {
      if (Array.isArray(this.value)) {
        if (event.target.checked === true) {
          this.$emit('input', [...this.value, this.val])
        } else {
          this.$emit('input', [
            ...this.value.filter(item => item !== this.val)
          ])
        }
      } else {
        if (event.target.checked === true) {
          this.$emit('input', true)
        } else {
          this.$emit('input', false)
        }
      }
    }
  }
}
</script>

<style scoped>
.vb-switch {
  width: 60px;
  height: 34px;
  margin: 5px;
  position: relative;
  display: inline-block;
}

.vb-switch ~ label {
  margin-left: 10px;
}

/*-- md size --*/
.vb-switch--sm {
  transform: scale(0.8);
}

/*-- md size --*/
.vb-switch--md .vb-switch {
  transform: scale(1);
}

/*-- lg size --*/
.vb-switch--lg {
  transform: scale(1.2);
}

.vb-switch input {
  opacity: 0;
}

.vb-switch__slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.vb-switch__slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: 0.4s ease-out;
  transition: 0.4s ease-out;
}

input:checked + .vb-switch__slider {
  background-color: #2196f3;
}

input:focus + .vb-switch__slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked + .vb-switch__slider:before {
  transform: translateX(26px);
  -ms-transform: translateX(26px);
  -webkit-transform: translateX(26px);
}

/*-- normal style --*/
.vb-switch--normal .vb-switch__slider {
  border-radius: 5px;
}

.vb-switch--normal .vb-switch__slider:before {
  border-radius: 10%;
}

/*-- round style --*/
.vb-switch--round .vb-switch__slider {
  border-radius: 34px;
}

.vb-switch--round .vb-switch__slider:before {
  border-radius: 50%;
}

/*-- sharp style --*/
.vb-switch--sharp .vb-switch__slider {
  border-radius: 0px;
}

.vb-switch--sharp .vb-switch__slider:before {
  border-radius: 0px;
}
</style>
