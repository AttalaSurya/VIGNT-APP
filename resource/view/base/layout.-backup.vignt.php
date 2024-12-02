<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo baseconfig('appName') ?>
    </title>
    <script>
        function vigntajax(r) {
            var e = r.url,
                t = r.method || "GET",
                n = r.data || null,
                o = r.success || function() {},
                s = r.error || function() {};
            if ("GET" === t && n) {
                var a = [];
                for (var i in n) n.hasOwnProperty(i) && a.push(encodeURIComponent(i) + "=" + encodeURIComponent(n[i]));
                e = e + (-1 === e.indexOf("?") ? "?" : "&") + a.join("&")
            }
            var u = new XMLHttpRequest;
            u.open(t, e, !0), u.setRequestHeader("Content-Type", "application/json"), u.onreadystatechange = function() {
                if (4 === u.readyState) {
                    if (u.status >= 200 && u.status < 300) try {
                        var r = JSON.parse(u.responseText);
                        o(r)
                    } catch (e) {
                        console.error("JSON parsing error:", e), s(e)
                    } else console.error("HTTP error! Status:", u.status, "Response:", u.responseText), s(Error("HTTP error! Status: " + u.status))
                }
            }, u.onerror = function() {
                console.error("Network error"), s(Error("Network error"))
            }, u.send("GET" === t || "HEAD" === t ? null : JSON.stringify(n))
        }
    </script>
