<template>
  <input
    ref="tsTimepicker"
    class="ts-timepicker"
    type="text"
    readonly
    :value="output"
    @click="onClick"
    @focus="onFocus"
    @blur="onBlur"
    @keyup="onKeyup"
    @keydown="onKeydown"
  >
</template>

<script>
const INPUT_PATTERN = /^(\d\d):(\d\d)(?::(\d\d))?$/;
const FORMAT_PATTERN = /^(h{1,2}|H{1,2}):(m{1,2})(?::(s{1,2}))?$/;

export default {
  name: "TsTimepicker",

  props: {
    value: {
      type: String,
      default: "00:00"
    },
    format: {
      type: String,
      default: "hh:mm",
      validator(val) {
        return FORMAT_PATTERN.test(val);
      }
    }
  },

  data() {
    return {
      hour: 0,
      min: 0,
      sec: 0,
      currPos: 1
    };
  },

  watch: {
    value: function(val) {
      if (INPUT_PATTERN.test(val)) {
        let groups = INPUT_PATTERN.exec(val);
        this.hour = Number(groups[1]);
        this.min = Number(groups[2]);
        if (groups[3]) this.sec = Number(groups[3]);
      } else {
        this.hour = 0;
        this.min = 0;
        this.sec = 0;
      }
    }
  },

  computed: {
    output: function() {
      return `${this.toText(this.hour)} : ${this.toText(
        this.min
      )} : ${this.toText(this.sec)} `;
    }
  },

  created() {},

  mounted() {
    this.selectRange(0, 0);
  },

  destroyed() {},

  methods: {
    onClick(e) {
      e.preventDefault();
      let caretPosition = e.target.selectionStart;
      //console.log(caretPosition);
      if (caretPosition >= 0 && caretPosition <= 3) {
        this.currPos = 1;
        this.selectPosition(1);
      } else if (caretPosition >= 4 && caretPosition <= 8) {
        this.currPos = 2;
        this.selectPosition(2);
      } else if (caretPosition >= 9 && caretPosition <= 12) {
        this.currPos = 3;
        this.selectPosition(3);
      } else if (caretPosition > 12) {
        this.currPos = 1;
        this.selectPosition(1);
      }
    },

    onMousedown(e) {
      //
    },

    onFocus(e) {
      //console.log(e);
      e.preventDefault();
      let elm = e.target;
      if (elm.selectionStart === 0 && elm.selectionEnd === 0) {
        elm.selectionStart = 0;
        elm.selectionEnd = 2;
      }
    },

    onBlur(e) {
      e.preventDefault();
      this.$emit("input", `${this.toText(this.hour)}:${this.toText(this.min)}`);
    },

    onKeydown(e) {
      //console.log(e);
      e.preventDefault();
      if (e.key === "Backspace" || e.key === "Delete") {
        if (this.currPos === 1) {
          this.hour = 0;
        } else if (this.currPos === 2) {
          this.min = 0;
        } else if (this.currPos === 3) {
          this.sec = 0;
        }
      }
      if (e.key === "Tab") {
        this.currPos += 1;
      } else if (e.key === "ArrowUp") {
        if (this.currPos === 1) {
          this.hour++;
        } else if (this.currPos === 2) {
          this.min++;
        } else if (this.currPos === 3) {
          this.sec++;
        }
      } else if (e.key === "ArrowDown") {
        if (this.currPos === 1) {
          this.hour = this.hour - 1 > 0 ? this.hour - 1 : 0;
        } else if (this.currPos === 2) {
          this.min = this.min - 1 > 0 ? this.min - 1 : 0;
        } else if (this.currPos === 3) {
          this.sec = this.sec - 1 > 0 ? this.sec - 1 : 0;
        }
      } else if (e.key === "ArrowRight") {
        this.currPos += 1;
      } else if (e.key === "ArrowLeft") {
        this.currPos -= 1;
      } else if (
        (e.keyCode >= 48 && e.keyCode <= 57) ||
        (e.keyCode >= 96 && e.keyCode <= 105)
      ) {
        let val = parseInt(e.key);
        if (this.currPos === 1) {
          if (this.hour < 10) this.hour = this.hour * 10 + val;
          else this.hour = val;
          if (this.hour >= 10) this.currPos++;
        } else if (this.currPos === 2) {
          if (this.min < 10) this.min = this.min * 10 + val;
          else this.min = val;
          if (this.min >= 10) this.currPos++;
        } else if (this.currPos === 3) {
          if (this.sec < 10) this.sec = this.sec * 10 + val;
          else this.sec = val;
        }
      }
    },

    onKeyup(e) {
      this.selectPosition(this.currPos);
    },

    selectPosition(position) {
      this.$refs.tsTimepicker.focus();
      if (position < 1) position = 1;
      if (position > 3) position = 3;
      if (position === 1) {
        this.selectRange(0, 2);
      } else if (position === 2) {
        this.selectRange(5, 7);
      } else if (position === 3) {
        this.selectRange(10, 12);
      }
      this.currPos = position;
    },

    selectRange(start, end) {
      this.$refs.tsTimepicker.setSelectionRange(start, end);
    },

    toText(val) {
      if (val < 10) return `0${val}`;
      else if (val >= 10) return `${val}`;
    }
  }
};
</script>

<style scoped>
.ts-timepicker {
  cursor: default;
  caret-color: transparent !important;
  background-color: #fff;
}
</style>
