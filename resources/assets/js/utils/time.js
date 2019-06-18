
export default class Time {
  static toString({ date, locale = null, option = null }) {
    locale = locale || window.navigator.userLanguage || window.navigator.language;
    option = option || { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };

    return date.toLocaleString(locale, option);
  }

  static passedToString(date) {
    let secs = Math.floor((Date.now() - date) / 1000); // Get secs passed
    let mins = Math.floor(secs / 60);
    let hours = Math.floor(secs / 60 / 60);
    let days = Math.floor(secs / 60 / 60 / 24);
    let months = Math.floor(secs / 60 / 60 / 24 / 30);
    let years = Math.floor(secs / 60 / 60 / 24 / 30 / 12);

    if (secs < 5)
      return 'just now';
    if (secs >= 5 && secs < 60)
      return `${secs} ${secs > 1 ? 'seconds' : 'second'} ago`;
    if (mins >= 1 && mins < 60)
      return `${mins} ${mins > 1 ? 'minutes' : 'minute'} ago`;
    if (hours >= 1 && hours < 24)
      return `${hours} ${hours > 1 ? 'hours' : 'hour'} ago`;
    if (days > 0 && days < 30)
      return `${days} ${days > 1 ? 'days' : 'day'} ago`;
    if (months > 0 && months < 12)
      return `${months} ${months > 1 ? 'months' : 'month'} ago`;
    if (years > 0 && years < 5)
      return `${years} ${years > 1 ? 'years' : 'year'} ago`;
    return 'a long time ago';
  }
}