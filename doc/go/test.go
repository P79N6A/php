package main

import (
	"fmt"
	"time"
)

func main() {
	var status bool

	fmt.Println(status)
	fmt.Printf("Hello, world!\n") //这里如果没问题的话应该有自动补全
	var count = 10

	for i := 0; i < count; i++ {
		if i%2 == 0 {
			continue
		}
		fmt.Println(i)
	}
	var sec = 1

	time.Sleep(time.Duration(sec) * time.Second)
}
