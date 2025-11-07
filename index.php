<?php
// codex_project - by Rami 🔥

// تحميل مفتاح API من ملف .env
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    die("⚠️ ملف .env غير موجود!");
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$api_key = null;
foreach ($lines as $line) {
    if (strpos($line, 'OPENAI_API_KEY=') === 0) {
        $api_key = trim(substr($line, strlen('OPENAI_API_KEY=')));
        break;
    }
}

if (!$api_key) {
    die("❌ لم يتم العثور على مفتاح OpenAI API.");
}

// لو المستخدم أرسل طلب
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prompt = $_POST["prompt"] ?? "";
    if (empty($prompt)) {
        echo "<p style='color:red'>⚠️ الرجاء كتابة أمر لتوليد الكود.</p>";
    } else {
        // استدعاء API
        $ch = curl_init("https://api.openai.com/v1/completions");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $api_key"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                "model" => "gpt-3.5-turbo-instruct",
                "prompt" => $prompt,
                "max_tokens" => 500
            ])
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $output = $data["choices"][0]["text"] ?? "❌ لم يتم الحصول على استجابة.";

        echo "<h3>🔹 الناتج:</h3><pre style='background:#111;color:#0f0;padding:10px;border-radius:6px;'>$output</pre>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>Codex PHP – توليد أكواد</title>
  <style>
    body {
      background: #0d1117;
      color: #e6edf3;
      font-family: Tahoma, sans-serif;
      padding: 40px;
      text-align: center;
    }
    textarea {
      width: 80%;
      height: 120px;
      border-radius: 8px;
      padding: 10px;
      background: #151b24;
      color: #e6edf3;
      border: 1px solid #30363d;
    }
    button {
      background: #238636;
      border: none;
      color: white;
      padding: 10px 20px;
      margin-top: 10px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background: #2ea043;
    }
  </style>
</head>
<body>
  <h1>🤖 Codex PHP – مولّد الأكواد</h1>
  <form method="POST">
    <textarea name="prompt" placeholder="اكتب طلبك هنا... مثال: أنشئ كود PHP يطبع كلمة Rami"></textarea><br>
    <button type="submit">توليد الكود</button>
  </form>
</body>
</html>
<?php
echo "Rami";
?>
