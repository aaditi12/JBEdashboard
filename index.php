<?php
require 'authentication.php'; // admin authentication check 

// auth check
if (isset($_SESSION['admin_id'])) {
    $user_id = $_SESSION['admin_id'];
    $user_name = $_SESSION['admin_name'];
    $security_key = $_SESSION['security_key'];
    if ($user_id !== NULL && $security_key !== NULL) {
        header('Location: task-info.php');
        exit; // Ensure no further code is executed after redirection
    }
}

if (isset($_POST['login_btn'])) {
    if (isset($_POST['admin_password'])) {
        $info = $obj_admin->admin_login_check($_POST);
    } else {
        $info = "Password is required.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = strtolower(trim($_POST['message']));
    $responses = [
        "what is jbe" => "JBE stands for Talent, Transformation, Technology, and Tax Automation.",
        "tell me about jbe" => "JBE focuses on providing innovative solutions in talent management and technology.",
        "services of jbe" => "JBE offers services in recruitment, digital transformation, IT solutions, and tax automation.",
        "contact jbe" => "You can contact JBE through their official website or customer support email."
    ];
    echo $responses[$message] ?? "I'm sorry, I don't have information on that. Try asking about JBE's services or mission.";
}

$page_name = "Login";
include("include/login_header.php");
?>

<style>
  html, body {
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    background: linear-gradient(135deg, #a2400b,#ff9800)
    animation: fadeInBody 1.5s ease-in-out;
  }

  .login-container {
    display: flex;
    width: 90%;
    max-width: 900px;
    background: white;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    background: linear-gradient(135deg, #a2400b,#ff9800)
    animation: popIn 1s ease-out;
  }

  .login-left {
    flex: 1;
    background: linear-gradient(to bottom, #ff7e5f, #feb47b);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    animation: slideInLeft 1s ease-out;
  }

  .login-left h2 {
    font-size: 24px;
    margin-bottom: 10px;
  }

  .login-left p {
    font-size: 16px;
  }

  .logo {
    max-width: 100%;
    height: auto;
    padding: 10px;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: white;
    transition: transform 0.3s ease-in-out;
  }

  .logo:hover {
    transform: scale(1.1);
  }

  .login-right {
    flex: 1;
    padding: 20px;
    background-color: hsl(0, 0%, 90%);
    animation: slideInRight 1s ease-out;
  }

  .form-group {
    margin-bottom: 15px;
    animation: fadeInForm 1.2s ease-out;
  }

  .form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: all 0.3s ease;
  }

  .form-group input:focus {
    border-color: #ff7e5f;
    box-shadow: 0 0 8px rgba(255, 126, 95, 0.6);
    outline: none;
  }

  .btn {
    background-color: orange;
    border-color: darkorange;
    color: white;
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .btn:hover {
    background-color: darkorange;
    transform: translateY(-3px);
  }

  @keyframes fadeInBody {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes popIn {
    0% { opacity: 0; transform: scale(0.8); }
    100% { opacity: 1; transform: scale(1); }
  }

  @keyframes slideInLeft {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
  }

  @keyframes slideInRight {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
  }

  @keyframes fadeInForm {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }


    /* Basic Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #F5F5F5, #FFFFFF);

        }
            /* background: linear-gradient(to right, #b3e0ff, #d4eaff);
        } */

        /* Container */
        .login-container {
            display: flex;
            width: 750px;
            background: white;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            
        }

        /* Left Side */
        .login-left {
            flex: 1;
            background: #F8F8F8;
            color: #E65200;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-left h2 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #E65200;
        }

        .login-left p {
            font-size: 14px;
            text-align: center;
            color: #E65200;

        }

        /* Right Side */
        .login-right {
            flex: 1;
            padding: 40px;
            background: white;
            text-align: center;
        }

        .login-right h2 {
            color: #E65200;
            margin-bottom: 20px;
        }

        /* Input Fields */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        /* Forgot Password */
        .forgot-password {
            text-align: right;
            margin-bottom: 15px;
        }

        .forgot-password a {
            color: royalblue;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Sign In Button */
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #ff9800, #e65100);
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease-in-out, transform 0.2s;
        }

        .btn:hover {
            transform: scale(1.05);
        }


          #chatbot-container {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 300px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        display: none;
    }
    #chatbot-header {
        background: #ff9800;
        color: white;
        padding: 10px;
        text-align: center;
        font-weight: bold;
    }
    #chatbot-messages {
        height: 200px;
        overflow-y: auto;
        padding: 10px;
    }
    #chatbot-input {
        width: 70%;
        padding: 5px;
    }
    #chatbot-send {
        width: 25%;
        background: #ff9800;
        color: white;
        border: none;
        cursor: pointer;
    }
    #chatbot-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #ff9800;
        color: white;
        padding: 10px;
        border-radius: 50%;
        cursor: pointer;
    }


    
</style>
<link rel="stylesheet" href="custom.css">


<div class="login-container">
  <button id="chatbot-toggle">💬 Chat</button>

<!-- Chatbot Container -->
<div id="chatbot-container">
    <div id="chatbot-header">
        <span>Chatbot</span>
        <button id="close-chat">✖</button>
    </div>
    <div id="chatbot-messages"></div>
    <div id="chatbot-input-container">
        <input type="text" id="chatbot-input" placeholder="Type a message...">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

  <div class="login-left">
    <h2><strong>JBE Talent Dashboard</Strong></h2><br>
    <img src="assets/img/logo.jpg" alt="JBE logo" class="logo"><br>
    <p><strong>Talent.Transformation.Technology.Tax Automation</strong></p>
  </div>
  <div class="login-right">
    <form class="form-horizontal form-custom-login" action="" method="POST">
      <div class="form-heading">
        <h2 class="text-center"><strong>Pathiks Login</strong></h2>
      </div>
      <?php if (isset($info)) { ?>
        <h5 class="alert alert-danger"><?php echo $info; ?></h5>
      <?php } ?>
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Username" name="username" required />
      </div>
      <div class="form-group">
        <input type="password" class="form-control" placeholder="Password" name="admin_password" required />
      </div>
      <button type="submit" name="login_btn" class="btn">Login</button>

    </form>
  </div>
</div>


<script>
document.getElementById("chatbot-toggle").addEventListener("click", function () {
    document.getElementById("chatbot-container").style.display = "flex";
    displayJBEQuestions();
});

document.getElementById("close-chat").addEventListener("click", function () {
    document.getElementById("chatbot-container").style.display = "none";
});

document.getElementById("chatbot-input").addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
        sendMessage();
    }
});

function sendMessage(inputText = null) {
    let input = inputText || document.getElementById("chatbot-input").value.trim().toLowerCase();
    let chatbox = document.getElementById("chatbot-messages");

    if (input !== "") {
        let response = getAIResponse(input);

        let responseContainer = document.createElement("div");
        responseContainer.classList.add("message-container");

        responseContainer.innerHTML = `
            <div class='user-message'>${input}</div>
            <div class='bot-message'>${response}</div>
        `;

        chatbox.appendChild(responseContainer);
        chatbox.scrollTop = chatbox.scrollHeight;

        document.getElementById("chatbot-input").value = "";
    }
}

function getAIResponse(input) {
    let keywords = {
        "talent": "JBE Talent matches the right opportunities with the right candidates.",
        "technology": "JBE Technology delivers digital transformation and automation.",
        "transformation": "JBE Transformation enhances productivity and efficiency.",
        "tax": "JBE Tax Automation offers accurate solutions for tax needs.",
        "lead": "Would you like to share your contact details for further assistance?",
        "dashboard": "The dashboard provides insights into analytics, user activity, reports, and system performance. It includes real-time metrics, graphical data representations, and customizable widgets. What specific details would you like to know?"
    };

    if (input.includes("how does the dashboard work")) {
        return "The dashboard has multiple sections for management functions including Task Management, Attendance, and Administration. Users can assign new tasks, monitor attendance, and generate detailed reports. The admin panel allows for task and employee management, while employees can interact with assigned tasks and track their performance.";
    }

    if (input.includes("full detail of dashboard")) {
        return "The dashboard consists of several key sections: Task Management, Attendance Tracking, and Administration. It includes a Task List displaying titles, assigned personnel, start/end times, and statuses. The Attendance section logs user in-time, out-time, and total working hours. Reports provide daily summaries and insights into performance. Admins can manage users, assign tasks, and access data visualizations for better decision-making.";
    }

    for (let key in keywords) {
        if (input.includes(key)) {
            return keywords[key];
        }
    }
    return "I'm not sure about that. Can I assist you with Talent, Technology, Transformation, Tax Automation, or Dashboard-related queries?";
}

function displayJBEQuestions() {
    let chatbox = document.getElementById("chatbot-messages");
    chatbox.innerHTML = "";

    let buttonContainer = document.createElement("div");
    buttonContainer.classList.add("button-container");
    buttonContainer.innerHTML = `<p class='bot-message'>Here are some questions you can ask:</p>`;

    let questions = [
        "What is JBE Talent?",
        "What is JBE Technology?",
        "What is JBE Transformation?",
        "What is JBE Tax Automation?",
        "How does the dashboard work?",
        "Give full details of the dashboard"
    ];

    questions.forEach(question => {
        let button = document.createElement("button");
        button.innerText = question;
        button.classList.add("question-button");
        button.onclick = function () { sendMessage(question.toLowerCase()); };
        buttonContainer.appendChild(button);
    });

    chatbox.appendChild(buttonContainer);

    let enquiryButton = document.createElement("button");
    enquiryButton.innerText = "Send an Enquiry";
    enquiryButton.classList.add("question-button");
    enquiryButton.onclick = function () { showEnquiryForm(); };
    buttonContainer.appendChild(enquiryButton);
}

function showEnquiryForm() {
    let chatbox = document.getElementById("chatbot-messages");
    chatbox.innerHTML = "";

    let formContainer = document.createElement("div");
    formContainer.classList.add("form-container");
    formContainer.innerHTML = `
        <p class='bot-message'>Please fill out the form below:</p>
        <input type='text' id='enquiry-name' placeholder='Your Name' class='form-input'>
        <input type='email' id='enquiry-email' placeholder='Your Email' class='form-input'>
        <textarea id='enquiry-message' placeholder='Your Enquiry' class='form-input'></textarea>
        <button onclick='sendEnquiry()' class='question-button'>Submit</button>
    `;

    chatbox.appendChild(formContainer);
}

function sendEnquiry() {
    let name = document.getElementById("enquiry-name").value;
    let email = document.getElementById("enquiry-email").value;
    let message = document.getElementById("enquiry-message").value;

    let subject = encodeURIComponent("Enquiry from Chatbot");
    let body = encodeURIComponent(`Name: ${name}\nEmail: ${email}\nMessage: ${message}`);
    
    window.location.href = `mailto:avinash.sharma@jyotirgamay.online?subject=${subject}&body=${body}`;
}

// Styling for Vertical Chatbot Layout
const style = document.createElement('style');
style.innerHTML = `
   #chatbot-container {
    position: fixed;
    right: 20px;
    bottom: 20px;
    width: 350px;
    height: 500px;
    display: none;
    flex-direction: column;
    border: none;
    background-color: white;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
    border-radius: 12px;
    overflow: hidden;
    font-family: 'Arial', sans-serif;
    transition: all 0.3s ease-in-out;
}

/* Chatbot Header */
#chatbot-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #007bff;
    color: white;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
}

#chatbot-header button {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
}

/* Chat Messages */
#chatbot-messages {
    flex-grow: 1;
    padding: 15px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    scrollbar-width: thin;
    scrollbar-color: #007bff #f1f1f1;
}

/* Custom Scrollbar */
#chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

#chatbot-messages::-webkit-scrollbar-thumb {
    background: #007bff;
    border-radius: 3px;
}

/* Input Box */
#chatbot-input-container {
    display: flex;
    padding: 12px;
    border-top: 1px solid #ccc;
    background: #f9f9f9;
}

#chatbot-input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
}

#chatbot-send {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    margin-left: 8px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s;
}

#chatbot-send:hover {
    background-color: #0056b3;
}

/* Messages Styling */
.message-container {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
}

.user-message, .bot-message {
    max-width: 75%;
    padding: 12px;
    border-radius: 10px;
    margin: 5px 0;
    font-size: 14px;
}

.user-message {
    background-color: #dcf8c6;
    align-self: flex-end;
    border-bottom-right-radius: 2px;
}

.bot-message {
    background-color: #f1f1f1;
    align-self: flex-start;
    border-bottom-left-radius: 2px;
}

/* Chatbot Animation */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-container {
    animation: slideIn 0.3s ease-in-out;
}
`;
document.head.appendChild(style);

</script>

<?php
include("include/footer.php");
?>


