<template>
  <!-- vb-timepicker -->
  <div
    ref="vbTimepicker"
    class="vb-timepicker">
    <input
      type="text"
      class="vb-timepicker__input form-control"
      :value="inputText"
      @focus="dropdownStatus = true"
      readonly>
    <span class="vb-timepicker__btn-clear"/>

    <!-- dropdown -->
    <div
      ref="dropdown"
      class="vb-timepicker__dropdown"
      v-show="dropdownStatus">
      <ul
        v-if="groups[1] !== undefined"
        class="vb-timepicker__dropdown__list">
        <li class="vb-timepicker__dropdown__list__title">{{ showGroup(1) }}</li>
        <li
          class="vb-timepicker__dropdown__list__option"
          v-for="n in hours"
          :key="n"
          :class="[{ 'vb-timepicker__dropdown__list__option--selected' : isSelected(n,1) }]"
          @click="selectHandler(n, 1)">
          {{ toText(n, 1) }}
        </li>
      </ul>
      <ul
        v-if="groups[2] !== undefined"
        class="vb-timepicker__dropdown__list">
        <li class="vb-timepicker__dropdown__list__title">{{ showGroup(2) }}</li>
        <li
          class="vb-timepicker__dropdown__list__option"
          v-for="n in mins"
          :key="n"
          :class="[{ 'vb-timepicker__dropdown__list__option--selected' : isSelected(n,2) }]"
          @click="selectHandler(n, 2)">
          {{ toText(n, 2) }}
        </li>
      </ul>
      <ul
        v-if="groups[3] != undefined"
        class="vb-timepicker__dropdown__list">
        <li class="vb-timepicker__dropdown__list__title">{{ showGroup(3) }}</li>
        <li
          class="vb-timepicker__dropdown__list__option"
          v-for="n in secs"
          :key="n"
          :class="[{ 'vb-timepicker__dropdown__list__option--selected' : isSelected(n,3) }]"
          @click="selectHandler(n, 3)">
          {{ toText(n, 3) }}
        </li>
      </ul>
      <ul
        v-if="groups[4] !== undefined"
        class="vb-timepicker__dropdown__list">
        <li class="vb-timepicker__dropdown__list__title">{{ showGroup(4) }}</li>
        <template v-if="this.groups[4] == 'a'">
          <li
            class="vb-timepicker__dropdown__list__option"
            :class="[{ 'vb-timepicker__dropdown__list__option--selected' : isSelected('am',4) }]"
            @click="selectHandler('am', 4)">am</li>
          <li
            class="vb-timepicker__dropdown__list__option"
            :class="[{ 'vb-timepicker__dropdown__list__option--selected' : isSelected('pm',4) }]"
            @click="selectHandler('pm', 4)">pm</li>
        </template>
        <template v-if="this.groups[4] == 'A'">
          <li
            class="vb-timepicker__dropdown__list__option"
            :class="[{ 'vb-timepicker__dropdown__list__option--selected' : isSelected('AM',4) }]"
            @click="selectHandler('AM', 4)">AM</li>
          <li
            class="vb-timepicker__dropdown__list__option"
            :class="[{ 'vb-timepicker__dropdown__list__option--selected' : isSelected('PM',4) }]"
            @click="selectHandler('PM', 4)">PM</li>
        </template>
      </ul>
    </div><!-- dropdown END -->
  </div><!-- vb-timepicker END -->
</template>

<script>
const PATTERN = /^(h{1,2}|H{1,2}):(m{1,2})(?::(s{1,2}))?(?:\s(a|A))?$/

export default {
  name: 'VbTimepicker',

  props: {
    value: {
      type: Object,
      default: undefined
    },
    format: {
      type: String,
      default: 'hh:mm',
      validator (value) {
        return PATTERN.test(value)
      }
    },
    minInterval: {
      type: Number,
      default: 1,
      validator (value) {
        return value > 0 && value < 59
      }
    },
    secInterval: {
      type: Number,
      default: 1,
      validator (value) {
        return value > 0 && value < 59
      }
    }
  },

  data () {
    return {
      groups: this.getGroups(this.format),
      dropdownStatus: false,
      hh: '',
      h: '',
      HH: '',
      H: '',
      mm: '',
      m: '',
      ss: '',
      s: '',
      a: '',
      A: ''
    }
  },

  watch: {
    format: function (value) {
      this.getGroups(value)
    },

    dropdownStatus: function (value) {
      if (value) {
        this.$refs.dropdown.classList.remove('vb-timepicker__dropdown--close')
        this.$refs.dropdown.classList.add('vb-timepicker__dropdown--open')
      } else {
        this.$refs.dropdown.classList.remove('vb-timepicker__dropdown--open')
        this.$refs.dropdown.classList.add('vb-timepicker__dropdown--close')
      }
    }
  },

  computed: {
    hours: function () {
      if (this.groups[1] === 'hh' || this.groups[1] === 'h') {
        return Array.from(new Array(12), (v, i) => (v = i + 1))
      } else if (this.groups[1] === 'HH' || this.groups[1] === 'H') {
        return Array.from(new Array(24), (v, i) => (v = i))
      }
    },

    mins: function () {
      return Array.from(
        new Array(Math.floor(59 / this.minInterval)),
        (v, i) => (v = (i + 1) * this.minInterval)
      )
    },

    secs: function () {
      return Array.from(
        new Array(Math.floor(59 / this.secInterval)),
        (v, i) => (v = (i + 1) * this.secInterval)
      )
    },

    inputText: function () {
      let hour =
        this.$data[this.groups[1]] !== ''
          ? this.$data[this.groups[1]]
          : this.groups[1]
      let min =
        this.$data[this.groups[2]] !== ''
          ? this.$data[this.groups[2]]
          : this.groups[2]
      let sec = ''
      let frm = ''
      if (this.groups[3] !== undefined) {
        sec =
          ':' +
          (this.$data[this.groups[3]] !== ''
            ? this.$data[this.groups[3]]
            : this.groups[3])
      }
      if (this.groups[4] !== undefined) {
        frm =
          ' ' +
          (this.$data[this.groups[4]] !== ''
            ? this.$data[this.groups[4]]
            : this.groups[4])
      }
      return `${hour}:${min}${sec}${frm}`
    },

    is12Hour: function () {
      return this.groups[1] === 'h' || this.groups[1] === 'hh'
    },

    is24Hour: function () {
      return this.groups[1] === 'H' || this.groups[1] === 'HH'
    }
  },

  created () {
    document.addEventListener('click', this.documentClickHandler)
  },

  mounted () {},

  destroyed () {
    document.removeEventListener('click', this.documentClickHandler)
  },

  methods: {
    selectHandler (value, groupNum) {
      if (groupNum === 1) {
        if (this.is12Hour) {
          this.h = this.H = value.toString()
          this.hh = this.HH = value < 10 ? `0${value}` : value.toString()
          this.a = 'am'
          this.A = 'AM'
          if (value === 12) {
            this.H = '13'
            this.HH = '13'
            this.a = 'pm'
            this.A = 'PM'
          }
        } else {
          if (value < 12) {
            this.h = this.H = value.toString()
            this.hh = this.HH = value < 10 ? `0${value}` : value.toString()
            this.a = 'am'
            this.A = 'AM'
            if (value === 0) {
              this.h = '12'
              this.hh = '12'
            }
          }
          if (value === 12) {
            this.h = this.H = this.hh = this.HH = value.toString()
            this.a = 'pm'
            this.A = 'PM'
          }
          if (value > 12) {
            this.h = (value - 12).toString()
            this.hh =
              value - 12 < 10 ? `0${value - 12}` : (value - 12).toString()
            this.H = this.HH = value.toString()
            this.a = 'pm'
            this.A = 'PM'
          }
        }
      }

      if (groupNum === 2) {
        this.m = value.toString()
        this.mm = value < 10 ? `0${value}` : value.toString()
      }

      if (groupNum === 3) {
        this.s = value.toString()
        this.ss = value < 10 ? `0${value}` : value.toString()
      }

      if (groupNum === 4) {
        this.a = value.toLowerCase()
        this.A = value.toUpperCase()
        if (this.is12Hour && this.h !== '') {
          let hour = parseInt(this.h)
          if (this.a === 'am') {
            this.h = this.H = hour.toString()
            this.hh = this.HH = hour < 10 ? `0${hour}` : hour.toString()
            if (hour === 12) {
              this.H = '0'
              this.HH = '00'
            }
          } else {
            this.h = hour.toString()
            this.hh = hour < 10 ? `0${hour}` : hour.toString()
            this.H = this.HH = (hour + 12).toString()
            if (hour === 12) {
              this.H = '12'
              this.HH = '12'
            }
          }
        }
      }

      this.$emit('input', {
        h: this.h,
        hh: this.hh,
        H: this.H,
        HH: this.HH,
        m: this.m,
        mm: this.mm,
        s: this.s,
        ss: this.ss,
        a: this.a,
        A: this.A
      })
    },

    isSelected (value, groupNum) {
      if (groupNum !== 4) {
        return (
          this.$data[this.groups[groupNum]] === this.toText(value, groupNum)
        )
      } else return this.$data[this.groups[groupNum]] === value
    },

    getGroups (format) {
      if (PATTERN.test(format)) {
        let pattern = PATTERN.exec(format)
        if (
          (pattern[1] === 'H' || pattern[1] === 'HH') &&
          pattern[4] !== undefined
        ) {
          pattern[4] = undefined
        }
        return pattern
      } else {
        throw new Error(`Invalid format for VbTimepicker. ${format}`)
      }
    },

    toText (value, groupNum) {
      if (this.groups[groupNum] === undefined) return
      if (this.groups[groupNum].length > 1 && value < 10) return `0${value}`
      return value.toString()
    },

    showGroup (groupNum) {
      return this.groups[groupNum]
    },

    documentClickHandler (event) {
      let target = event.target
      let elm = this.$refs.vbTimepicker
      if (elm !== target && !elm.contains(target)) this.dropdownStatus = false
    }
  }
}
</script>

<style scoped>
.vb-timepicker {
  user-select: none;
  position: relative;
}

.vb-timepicker__input {
  cursor: pointer;
  background-color: white;
}

.vb-timepicker__dropdown {
  display: block;
  overflow: show;
  position: absolute;
  top: 100%;
  left: 0px;
  width: auto;
  z-index: 10;
  margin-top: 0px;
  border-top: 0px;
  background-color: white;
  border: 1px solid #ced4da;
  padding: 10px 10px 10px 10px;
  border-radius: 0px 0px 5px 5px;
}

.vb-timepicker__dropdown--open {
  animation-name: dropdown-open;
  animation-duration: 0.3s;
  opacity: 1;
}

.vb-timepicker__dropdown--close {
  animation-name: dropdown-close;
  animation-duration: 0.3s;
  opacity: 0;
}

@keyframes dropdown-open {
  from {
    top: -30px;
    opacity: 0;
  }
  to {
    top: 100%;
    opacity: 1;
  }
}

@keyframes dropdown-close {
  from {
    top: 100%;
    opacity: 1;
  }
  to {
    top: -30px;
    opacity: 0;
  }
}

.vb-timepicker__dropdown__list {
  margin: 0px;
  padding: 0px;
  max-height: 200px;
  overflow-y: auto;
  list-style: none;
  vertical-align: top;
  display: inline-block;
  min-width: 50px;
}

.vb-timepicker__dropdown__list__title {
  padding: 5px;
  cursor: default;
  font-size: 10px;
  color: rgba(0, 0, 0, 0.3);
}

.vb-timepicker__dropdown__list__option {
  cursor: pointer;
  padding: 2px 10px;
}

.vb-timepicker__dropdown__list__option:hover {
  background-color: rgba(0, 0, 0, 0.1);
}

.vb-timepicker__dropdown__list__option--selected {
  color: white;
  background-color: #8bc34a !important;
}
</style>
