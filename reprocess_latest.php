<?php
// Load database connection dynamically (which automatically includes env_loader.php)
require_once __DIR__ . '/includes/db_connect.php';

$GROQ_API_KEY = getGroqApiKey();

try {
    $stmt = $pdo->query('SELECT id, transcript FROM interviews ORDER BY id DESC LIMIT 1');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $id = $row['id'];
        $transcript = $row['transcript'];
        
        $prompt = "You are an expert technical recruiter and hiring manager. Provide a brief, concise executive summary of the following interview transcript using bullet points. Please intelligently auto-correct obvious speech-to-text transcription errors (for example, interpreting 'B.T.E.' as 'B.Tech', or 'KIT' as 'KIIT'). At the very end of your response, you MUST clearly state the final hiring decision as exactly '**VERDICT: SELECTED**' or '**VERDICT: REJECTED**', along with a one-sentence justification. Be decisive based on the candidate's clarity, skills, and relevance to the role.\n\nTranscript:\n" . $transcript;
        
        $llmData = json_encode([
            "model" => "llama-3.3-70b-versatile",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a helpful and precise HR assistant."
                ],
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ]
        ]);

        $ch2 = curl_init();
        curl_setopt_array($ch2, array(
            CURLOPT_URL => "https://api.groq.com/openai/v1/chat/completions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $llmData,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $GROQ_API_KEY",
                "Content-Type: application/json"
            ),
        ));
        $response2 = curl_exec($ch2);
        curl_close($ch2);

        $summaryData = json_decode($response2, true);
        
        if (isset($summaryData['choices'][0]['message']['content'])) {
            $new_summary = $summaryData['choices'][0]['message']['content'];
            $update = $pdo->prepare("UPDATE interviews SET summary = ? WHERE id = ?");
            $update->execute([$new_summary, $id]);
            echo "Successfully reprocessed interview #" . $id;
        } else {
            echo "Failed to get new summary from API.";
        }
    } else {
        echo "No interviews found.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
