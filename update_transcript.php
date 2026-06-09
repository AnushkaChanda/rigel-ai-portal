<?php


try {
    require_once __DIR__ . '/includes/db_connect.php';

    $realTranscript = "Hi, I'm Samir Das, a B.Tech Information Technology student at KIIT University with interests in AI, machine learning, and technology-driven solutions. I enjoy working on technical projects, content creation, and learning skills that create real-world impact. I would like to apply for the AI/Technical Development domain because I am passionate about building intelligent applications and solving practical problems. This domain aligns well with my academic background and career goals in AI and data science. I have worked on projects such as Wine Quality Prediction, Deepfake Detection using CNNs, and healthcare-focused machine learning models. I have also gained experience with Python, Pandas, Scikit-learn, and basic web technologies through academic and personal projects. I expect to gain hands-on industry experience, improve my technical and teamwork skills, and contribute meaningfully to Rigel's projects. The internship will help me bridge the gap between classroom learning and real-world application while preparing me for future opportunities.";
    
    // Update the latest interview transcript
    $stmt = $pdo->query('SELECT id FROM interviews ORDER BY id DESC LIMIT 1');
    $row = $stmt->fetch();
    if($row) {
        $update = $pdo->prepare("UPDATE interviews SET transcript = ? WHERE id = ?");
        $update->execute([$realTranscript, $row['id']]);
        echo "Transcript updated for interview #" . $row['id'];
    }
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage();
}
?>
