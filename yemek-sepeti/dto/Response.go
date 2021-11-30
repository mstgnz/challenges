package dto

type Response struct {
	Status  bool          "json:status"
	Message string        "json:code"
	Stores  []KeyValueDto "json:stores"
}
