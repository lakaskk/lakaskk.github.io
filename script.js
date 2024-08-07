function toggleLanguage() {
    const elements = {
        th: {
            name: "เอกลักษณ์ ปิ่นมณี",
            nickname: "ชื่อเล่น: โอม",
            personalInfoTitle: "ประวัติส่วนตัว",
            age: "<strong>อายุ:</strong> 25 ปี",
            contactTitle: "ช่องทางการติดต่อ",
            goalTitle: "เป้าหมายในการทำงาน",
            educationTitle: "การศึกษา",
            skillsTitle: "ทักษะความสามารถ",
            workTitle: "ประวัติการทำงาน",
            goalText: "หลังจากเรียนจบสาขาคอมพิวเตอร์ธุรกิจ มีเป้าหมายในการทำงานตามทักษะที่ได้เรียนมา ตั้งแต่การเขียนโปรแกรมบนเว็บ ไปใช้ในการทำงาน และอยากพัฒนาฝีมือด้านการเขียนเว็บไซต์ในอนาคต",
            educationText: "มหาวิทยาลัยราชภัฏสวนสุนันทา (2018 - 2024) ปริญญาตรี สาขาคอมพิวเตอร์ธุรกิจ",
            skillsList: [
                "เขียน HTML/CSS/JavaScript ได้เบื้องต้น",
                "ใช้ Microsoft Office ได้",
                "ภาษาอังกฤษ สามารถอ่านและฟังได้เข้าใจ",
                "สามารถแก้ปัญหา/ซ่อมคอมพิวเตอร์ได้"
            ],
            workText: "2024 นักศึกษาฝึกงาน บริษัท มีราคัลโอสโฟ้ จำกัด - ฝึกงานตัดต่อวิดีโอ ทำคลิปรีวิววิดีโอ"
        },
        en: {
            name: "Ekkalak Pinmanee",
            nickname: "Nickname: Ohm",
            personalInfoTitle: "Personal Information",
            age: "<strong>Age:</strong> 25",
            contactTitle: "Contact Information",
            goalTitle: "Career Goals",
            educationTitle: "Education",
            skillsTitle: "Skills",
            workTitle: "Work Experience",
            goalText: "After graduating in Business Computer, I aim to work according to the skills I have learned, starting from web programming, applying it in work, and aiming to develop my website writing skills in the future.",
            educationText: "Suan Sunandha Rajabhat University (2018 - 2024) Bachelor's Degree in Business Computer",
            skillsList: [
                "Basic HTML/CSS/JavaScript",
                "Proficient in Microsoft Office",
                "English: Able to read and understand",
                "Able to troubleshoot/repair computers"
            ],
            workText: "2024 Internship at Miracalosefo Ltd. - Video Editing, Video Review Production"
        }
    };

    const currentLang = document.documentElement.lang;
    const newLang = currentLang === "th" ? "en" : "th";

    document.documentElement.lang = newLang;
    const data = elements[newLang];

    document.getElementById("name").textContent = data.name;
    document.getElementById("nickname").textContent = data.nickname;
    document.getElementById("personal-info-title").textContent = data.personalInfoTitle;
    document.getElementById("age").textContent = data.age;
    document.getElementById("contact-title").textContent = data.contactTitle;
    document.getElementById("goal-title").textContent = data.goalTitle;
    document.getElementById("education-title").textContent = data.educationTitle;
    document.getElementById("skills-title").textContent = data.skillsTitle;
    document.getElementById("work-title").textContent = data.workTitle;

    document.getElementById("goal-text").textContent = data.goalText;
    document.getElementById("education-text").textContent = data.educationText;

    const skillsList = document.getElementById("skills-list");
    skillsList.innerHTML = "";
    data.skillsList.forEach(skill => {
        const li = document.createElement("li");
        li.textContent = skill;
        skillsList.appendChild(li);
    });

    document.getElementById("work-text").textContent = data.workText;
}