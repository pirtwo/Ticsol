export function env() {
  let host = stripHostWWW(window.location.hostname);
  switch (host) {
    case 'app.ticsol.com.au':
      return 'server';
    case 'test.app.ticsol.com.au':
      return 'test';
    case 'server.dev':
      return 'local';
    default:
      return "";
  }
}

export function origin() {
  return `https://${hostname()}`;
}

export function hostname() {
  return stripHostWWW(window.location.hostname);
}

function stripHostWWW(host) {
  host = host.toLowerCase();
  if (host.indexOf('www.') > -1) {
    return host.substring(4);
  } else {
    return host;
  }
}