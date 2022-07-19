@extends('layouts.app')

@push('styles')
    <style>
        #launchBtn {
            bottom: 100px;
            right: 80px;
            positison: fixed;
            border-radius: 100%;
        }

        .senderContainer {
            width: 100%;
            margin-bottom: 30px;
        }

        .senderText {
            background-color: #9b4373;
            color: white;
            border-radius: 20px;
        }

        .chatSender {
            font-weight: bold;
        }

        /Bagian jawaban/ .answerContainer {
            width: 100%;

            margin-bottom: 30px;
        }

        .answerText {
            background-color: #6c467b;

            color: white;

            border-radius: 20px;
        }

        /Bagian input pertanyaan/ #inputContainer {
            bottom: 60px;
            position: fixed;
            width: 50%;
        }

        main {
            background-color: #df7582;
            c
        }
    </style>
@endpush

@section('content')
    <div class="container content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <center>Chatbot LAA</center>
                    </div>
                    <div class="card-body height3">

                        <div class="container">
                            <div class="overflow-auto row" style="height:400px;">
                                <div class="col-12" id="chatbox"></div>
                            </div>
                            <div class="form-group row" id="inputContainer">

                                <div class="col-10">
                                    <input class="form-control" type="text" id="question">
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-outline-danger" id="send">Kirim</button>

                                </div>
                            </div>
                        </div>


                        <!-- Optional JavaScript -->
                        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
                        <script>
                            const questionBox = document.getElementById("question");
                            const chatBox = document.getElementById("chatbox");

                            const sendBtn = document.getElementById("send");
                            const parseText = (answer) => {
                                const splitted = answer.split(" ");
                                for (let i = 0; i < splitted.length; i++) {
                                    if (splitted[i].includes("https")) {
                                        splitted[i] = `<a href='${splitted[i]}' target='_blank'>link berikut</a>`;

                                    }
                                }
                                if (answer.includes("Silahkan pilih yang anda maksud")) {
                                    const splitWithObject = answer.split("Silahkan pilih yang anda maksud");
                                    console.log(splitWithObject)
                                    for (let i = 0; i < splitWithObject.length; i++) {
                                        if (splitWithObject[i].includes("{")) {
                                            splitWithObject[i] = splitWithObject[i].trim();
                                            const jsonFormatted = JSON.parse(splitWithObject[i]);

                                            let listFormatted = `<ol>`;
                                            jsonFormatted["listJawaban"].forEach((ans) => {
                                                listFormatted += `<li>${ans}</li>`;
                                            })
                                            listFormatted += `</ol>`
                                            splitWithObject[i] = listFormatted;
                                        }
                                    }
                                    return "Silahkan pilih yang anda maksud " + splitWithObject.join(" ");
                                }
                                return splitted.join(" ");
                            }

                            const sendMessage = async () => {
                                if (questionBox.value == "") {
                                    return
                                }
                                const data = {
                                    "question": questionBox.value
                                }
                                chatBox.innerHTML += `

            
        <div class="row senderContainer">
            <div class="col-6"></div>
            
            <div class="col-6 d-flex justify-content-between senderText">
                <div>
                    <p class='chatSender'>You</p>
                    <p>${questionBox.value}</p>
                </div>
                
            </div>
            
        </div>`;
                                const response = await fetch('http://localhost:5000/chatbot', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                });

                                const jsonRes = await response.json();

                                const formattedAnswer = parseText(jsonRes.answer);

                                chatBox.innerHTML += `
        <div class="row answerContainer">
       
            <div class="col-6 d-flex justify-content-between answerText">
                <div>
                    <p class='chatSender'>Bot LAA</p>
                    <p>${formattedAnswer}</p>
                </div>
            </div>
            <div class="col-6"></div>
        </div>`;
                                questionBox.value = "";
                            }
                            sendBtn.addEventListener("click", () => {
                                sendMessage();
                            });

                            questionBox.addEventListener("keypress", (event) => {
                                if (event.keyCode === 13) { // key code of the keybord key
                                    sendMessage();
                                    // your code to Run
                                }
                            });
                        </script>
                    @endsection
