package main

import (
	"fmt"
	"log"
	"net/http"
	"os"

	"yemek-sepeti/controller"
	"yemek-sepeti/entity"
	"yemek-sepeti/service"
)

var (
	kvEntity     entity.KeyValue                = entity.KeyValue{}
	kvService    service.IKeyValueService       = service.KeyValueService(kvEntity)
	kvController controller.IKeyValueController = controller.KeyValueController(kvService)
)

func main() {

	// initialize - read data.json
	kvService.Init()

	// cron
	go fakeCron()

	httpPort := 8181
	logPath := "development.log"
	openLogFile(logPath)

	log.SetFlags(log.Ldate | log.Ltime | log.Lshortfile)

	// GET : get all list
	http.HandleFunc("/api/", kvController.GetAll)

	// POST : get value with key -> normalde get olması gerekiyor ama net/http paketi patternde regex kabul etmediği için post olarak istek atılacak.
	// tabiki farklı yollarıda mevcut ama şu an tercih edilen yöntem bu şekildedir.
	http.HandleFunc("/api/key", kvController.GetKey)

	// POST : insert value with key
	http.HandleFunc("/api/insert", kvController.Insert)

	// PUT : update value with key
	http.HandleFunc("/api/update", kvController.Update)

	// DELETE : delete value with key
	http.HandleFunc("/api/delete", kvController.Delete)

	// SAVE : delete value with key
	http.HandleFunc("/api/save", kvController.Save)

	fmt.Printf("listening on %v\n", httpPort)
	fmt.Printf("Logging to %v\n", logPath)

	// start http
	err := http.ListenAndServe(fmt.Sprintf(":%d", httpPort), nil)
	if err != nil {
		log.Fatal(err)
	}
}

func openLogFile(logfile string) {
	if logfile != "" {
		lf, err := os.OpenFile(logfile, os.O_WRONLY|os.O_CREATE|os.O_APPEND, 0640)
		if err != nil {
			log.Fatal("OpenLogfile: os.OpenFile:", err)
		}
		log.SetOutput(lf)
	}
}

// fake cron job
func fakeCron(){

}