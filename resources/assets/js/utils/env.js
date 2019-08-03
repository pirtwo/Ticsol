export default function env() {
  return window.location.hostname == "server.dev" ? "local" : "server";
}