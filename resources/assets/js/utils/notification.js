import uuid from './uuid';

export default class Notification {
  constructor({
    type = 'info',
    title,
    message,
    autoHide = true,
    delay = 3000,
    seen = false,
    date = Date.now()
  }) {
    this.id = uuid();
    this.type = type;
    this.title = title;
    this.message = message;
    this.autoHide = autoHide;
    this.delay = delay;
    this.seen = seen;
    this.hide = false;
    this.date = date;
  }
}

