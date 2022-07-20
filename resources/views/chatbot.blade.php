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
            background-color: #d15298;
            color: white;
            border-radius: 20px;
        }

        .chatSender {
            font-weight: bold;
        }

        /*Bagian jawaban*/
        .answerContainer {
            width: 100%;

            margin-bottom: 30px;
        }

        .answerText {
            background-color: #f57063;

            color: white;

            border-radius: 20px;
        }

        /*Bagian input pertanyaan*/
        #inputContainer {
            width: 100%;
            margin-bottom: 30px;
        }

        main {
            background-color: #df7582;

        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <p class="text-center">
                    Chatbot LAA
                </p>
            </div>
            <div class="card-body ps-2">
                <div id="chatbox" style="height:320px;width:100%;overflow-y:auto">
                    <div class="row answerContainer ms-4">
                        <div class="col-6 answerText">
                            <p>Admin</p>

                            <p>Selamat Datang, Silakan bertanya seputar: </p>
                            <ol>
                                <li>Jadwal Mata Kuliah dan Ruangan Kelas</li>
                                <li>Jadwal Ujian</li>
                                <li>Cetak Kartu Ujian</li>
                                <li>Kuliah Hybrid</li>
                                <li>Nilai Akademik</li>
                                <li>Jadwal Masa Registrasi</li>
                                <li>Cuti Akademik</li>
                                <li>Total SKS</li>
                                <li>SKS Mata Kuliah</li>
                                <li>Beasiswa</li>
                                <li>Kalender Akademik</li>
                                <li>TAK</li>
                                <li>Pengajuan TAK</li>
                                <li>Verifikasi TAK</li>
                                <li>EPRT</li>
                                <li>Pendaftaran EPRT</li>
                                <li>Cek Data Pendaftaran EPRT</li>
                                <li>Input Nilai Tes Bahasa Dari Instansi Luar</li>
                                <li>Skor EPRT</li>
                                <li>Program Studi</li>
                                <li>Biaya Pendidikan</li>
                                <li>Kelas Internasional</li>
                                <li>Pendaftaran Mahasiswa Baru</li>
                                <li>Seleksi Mahasiswa Baru</li>
                                <li>Asrama Telkom</li>
                                <li>Virtual Tour Telkom</li>
                                <li>Nama Dosen</li>
                                <li>NIP Dosen</li>
                                <li>Nama Dosen Wali</li>
                                <li>NIP Dosen Wali</li>
                                <li>Ujian Susulan</li>
                                <li>Alur Pengajuan Ujian Susulan</li>
                                <li>Jadwal Kerja Praktek</li>
                                <li>Surat Penjajakan</li>
                                <li>Surat Izin Kerja Praktek </li>
                                <li>Buku Panduan Pelaksanaan Tugas Akhir </li>
                                <li>Format Proposal Tugas Akhir </li>
                                <li>Format Jurnal Tugas Akhir </li>
                                <li>Format Buku Tugas Akhir </li>
                                <li>Jadwal Sidang </li>
                                <li>SK TA/PA </li>
                                <li>Cek Status SK TA/PA </li>
                                <li>Pendaftaran SK TA/PA </li>
                                <li> Syarat Sidang </li>
                                <li>Daftar Sidang </li>
                            </ol>

                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <div class="row w-100" id="inputContainer">
                    <div class="col-9">
                        <input type="text" class="form-control" id="question">
                    </div>
                    <div class="col-3">
                        <button class="btn btn-outline-danger w-100" id="send">Kirim</button>
                    </div>
                </div>
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
            
            <div class="col-6 senderText">
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
       
            <div class="col-6  answerText ms-4">
                <div>
                    <p class='chatSender'>Bot LAA</p>
                    <p>${formattedAnswer}</p>
                </div>
            </div>
            <div class="col-6"></div>
        </div>`;
            questionBox.value = "";
            chatBox.lastChild.scrollIntoView(false)
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
