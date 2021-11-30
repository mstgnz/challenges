package entity

import (
	"errors"
	"github.com/mstgnz/yemek-sepeti-challange/dto"
)

// KeyValue -> Definition key value struct
type KeyValue struct {
	Key   string
	Value string
	Next  *KeyValue
}

// AddToEnd -> Add new element to end of list
func (node *KeyValue) AddToEnd(key, value string) bool {
	if len(node.Key) == 0 {
		node.Key = key
		node.Value = value
	}else{
		iter := node
		for iter.Next != nil {
			iter = iter.Next
		}
		iter.Next = &KeyValue{Key: key, Value: value, Next: nil}
	}
	return true
}

// Search -> Find key and return
func (node *KeyValue) Search(key string) (KeyValue, error) {
	for node.Next != nil && node.Next.Key == key {
		return *node.Next, nil
	}
	return *node, errors.New("not found key")
}

// Update -> Find key and update value
func (node *KeyValue) Update(key, value string) (KeyValue, error) {
	for node.Next != nil && node.Next.Key != key {
		node = node.Next
	}
	if node.Next != nil {
		node.Next.Value = value
		return *node.Next, nil
	}
	return *node, errors.New("not found key")
}

// Delete -> Find key and delete
func (node *KeyValue) Delete(key string) bool {
	for node.Next != nil && node.Next.Key != key {
		node = node.Next
	}
	if node.Next != nil {
		node.Next = node.Next.Next
		return true
	}
	return false
}

// Flush -> map the object
func (node *KeyValue) Flush() []dto.KeyValueDto {
	iter := node
	var listOfMaps []dto.KeyValueDto
	for iter != nil {
		listOfMaps = append(listOfMaps, dto.KeyValueDto{Key: iter.Key, Value: iter.Value})
		iter = iter.Next
	}
	return listOfMaps
}
