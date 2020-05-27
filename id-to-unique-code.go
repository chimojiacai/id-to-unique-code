package main

import (
	"github.com/spf13/cast"
	"math/rand"
	"fmt"
)

func main() {
	id := 90821
	fmt.Println("id转code:->", code.Id2Code(id))
	fmt.Println("code转id:->", code.Code2Id(code.Id2Code(id)))
}

var chars = []string{
	"9", "W", "6", "U", "X", "E", "7", "G", "S", "2", "R",
	"J", "8", "P", "5", "A", "3", "M", "Z", "F", "C", "4",
	"B", "N", "H", "L", "Y", "Q", "K", "V", "T"}

// 邀请码间隔码，因为有的邀请码是不足6位的，所以要有间隔码
const DIVIDER = "S"

// 最短设备码
const CODE_MIN_LENGTH = 6

// 获取唯一邀请码
func Id2Code(id int) string {
	buf := ""
	// 最大下标
	posMax := len(chars) - 1
	// 将10进制的id转化为33进制的邀请码
	for (id / len(chars)) > 0 {
		ind := id % len(chars)
		buf += chars[ind]
		id = cast.ToInt(id / len(chars))
	}
	buf += chars[cast.ToInt(id%len(chars))]
	// 反转buf字符串
	buf = reverse(buf)
	// 补充长度
	fixLen := CODE_MIN_LENGTH - len(buf)
	if fixLen > 0 {
		buf += DIVIDER
		for i := 0; i < fixLen-1; i++ {
			// 从字符序列中随机取出字符进行填充
			buf += chars[rand.Intn(posMax)]
		}
	}
	return buf
}

// 邀请码转id
func Code2Id(code string) int {
	codeLen := len(code)
	id := 0
	// 33进制转10进制
	for i := 0; i < codeLen; i++ {
		if string(code[i]) == DIVIDER {
			break
		}
		ind := 0
		for j := 0; j < len(chars); j++ {
			if string(code[i]) == chars[j] {
				ind = j
				break
			}
		}
		if i > 0 {
			id = id*len(chars) + ind
		} else {
			id = ind
		}
	}

	return id
}

// 字符串反转
func reverse(str string) string {
	var result []byte
	for i := len(str) - 1; i >= 0; i-- {
		result = append(result, str[i])
	}
	return string(result)
}
